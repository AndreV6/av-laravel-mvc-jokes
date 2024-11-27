<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Controller for managing jokes in the system
 */

namespace App\Http\Controllers;

use App\Models\Joke;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JokeController extends Controller
{
    /**
     * Display a list of jokes with search and filter functionality
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category');

        $jokes = Joke::query()
            ->with(['author', 'category'])
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('joke', 'like', "%{$search}%")
                        ->orWhere('tags', 'like', "%{$search}%")
                        ->orWhereHas('category', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($categoryId, function($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('jokes.index', compact('jokes', 'categories', 'search', 'categoryId'));
    }

    /**
     * Show form to create a new joke
     *
     * @return View
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('jokes.create', compact('categories'));
    }

    /**
     * Store a newly created joke
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'joke' => ['required', 'string', 'min:3'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'string', 'max:255'],
        ]);

        $joke = new Joke($validated);
        $joke->author_id = Auth::id();
        $joke->save();

        return redirect()->route('jokes.show', $joke)
            ->with('status', 'Joke created successfully.');
    }

    /**
     * Display a specific joke
     *
     * @param Joke $joke
     * @return View
     */
    public function show(Joke $joke): View
    {
        $joke->load(['author', 'category']);
        return view('jokes.show', compact('joke'));
    }

    /**
     * Show form to edit a joke
     *
     * @param Joke $joke
     * @return View
     */
    public function edit(Joke $joke): View
    {
        // Check if user owns this joke
        if ($joke->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::orderBy('name')->get();
        return view('jokes.edit', compact('joke', 'categories'));
    }

    /**
     * Update a joke
     *
     * @param Request $request
     * @param Joke $joke
     * @return RedirectResponse
     */
    public function update(Request $request, Joke $joke): RedirectResponse
    {
        // Check if user owns this joke
        if ($joke->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'joke' => ['required', 'string', 'min:3'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'string', 'max:255'],
        ]);

        $joke->update($validated);

        return redirect()->route('jokes.show', $joke)
            ->with('status', 'Joke updated successfully.');
    }

    /**
     * Delete a joke
     *
     * @param Joke $joke
     * @return RedirectResponse
     */
    public function destroy(Joke $joke): RedirectResponse
    {
        // Check if user owns this joke
        if ($joke->author_id !== Auth::id() && !auth()->user()->hasRole(['superuser', 'administrator', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $joke->delete();

        return redirect()->route('jokes.index')
            ->with('status', 'Joke deleted successfully.');
    }

    /**
     * Display trashed jokes
     *
     * @return View
     */
    public function trashed(): View
    {
        $jokes = Joke::onlyTrashed()->with(['author', 'category'])->paginate(10);
        return view('jokes.trashed', compact('jokes'));
    }

    /**
     * Restore a trashed joke
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function restore($id): RedirectResponse
    {
        $joke = Joke::withTrashed()->findOrFail($id);

        // Check permissions based on user role
        if (auth()->user()->hasRole('superuser')) {
            $joke->restore();
        } elseif (auth()->user()->hasRole('administrator')) {
            if (!$joke->author->hasRole('superuser')) {
                $joke->restore();
            }
        } elseif (auth()->user()->hasRole('staff')) {
            if (!$joke->author->hasAnyRole(['superuser', 'administrator', 'staff'])) {
                $joke->restore();
            }
        } elseif ($joke->author_id === auth()->id()) {
            $joke->restore();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->route('jokes.trashed')
            ->with('status', 'Joke restored successfully.');
    }

    /**
     * Permanently delete a joke
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function forceDelete($id): RedirectResponse
    {
        $joke = Joke::withTrashed()->findOrFail($id);

        // Check permissions based on user role
        if (auth()->user()->hasRole('superuser')) {
            $joke->forceDelete();
        } elseif (auth()->user()->hasRole('administrator')) {
            if (!$joke->author->hasRole('superuser')) {
                $joke->forceDelete();
            }
        } elseif (auth()->user()->hasRole('staff')) {
            if (!$joke->author->hasAnyRole(['superuser', 'administrator', 'staff'])) {
                $joke->forceDelete();
            }
        } elseif ($joke->author_id === auth()->id()) {
            $joke->forceDelete();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->route('jokes.trashed')
            ->with('status', 'Joke permanently deleted.');
    }
}
