<?php

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
     * Display a list of jokes with search, filter functionality
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
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('jokes.create', compact('categories'));
    }

    /**
     * Store a new joke
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
     * Display specific joke
     */
    public function show(Joke $joke): View
    {
        $joke->load(['author', 'category']);
        return view('jokes.show', compact('joke'));
    }

    /**
     * Show form to edit joke
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
     * Update joke
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
     * Delete joke
     */
    public function destroy(Joke $joke): RedirectResponse
    {
        // Check if user owns this joke
        if ($joke->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $joke->delete();

        return redirect()->route('jokes.index')
            ->with('status', 'Joke deleted successfully.');
    }
}
