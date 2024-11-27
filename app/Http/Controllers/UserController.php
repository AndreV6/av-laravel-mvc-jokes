<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a list of users
     * Includes search and filter functionality
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $users = User::query()
            ->when($search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nickname', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Show detailed information of a specific users
     */
    public function show(User $user): View
    {
        // Users can only view themselves or users they created
        if ($user->id !== Auth::id() && $user->created_by !== Auth::id() && !Auth::user()->hasRole(['superuser', 'administrator'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show form to edit users details
     */
    public function edit(User $user): View
    {
        // User can only edit themselves or users they created
        if ($user->id !== auth()->id() && $user->created_by !== auth()->id() && !Auth::user()->hasRole(['superuser', 'administrator'])) {
            abort(403, 'You can only edit your own account or accounts you created.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Show form to create a new users
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a new users
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'nickname' => $validated['nickname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('users.show', $user)
            ->with('status', 'User created successfully.');
    }

    /**
     * Update users details
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // User can only update themselves or users they created
        if ($user->id !== auth()->id() && $user->created_by !== auth()->id()) {
            abort(403, 'You can only update your own account or accounts you created.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('status', 'User updated successfully.');
    }

    /**
     * Delete a users
     */
    public function destroy(User $user): RedirectResponse
    {

        // Prevent deletion of last superuser
        if ($user->hasRole('superuser')) {
            $superuserCount = User::role('superuser')->count();
            if ($superuserCount <= 1) {
                return redirect()->back()->withErrors(['user' => 'Cannot delete the last superuser.']);
            }
        }

        $user->delete();

        if ($user->id === auth()->id() ||
            (auth()->user()->hasRole('administrator') && !$user->hasRole('superuser')) ||
            (auth()->user()->hasRole('staff') && !$user->hasAnyRole(['superuser', 'administrator', 'staff']))) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('status', 'User moved to trash.');
        }

        abort(403, 'Unauthorized action.');
    }

    public function trashed(): View
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('users.trashed', compact('users'));
    }

    public function restore($id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        // Check permissions based on user role
        if (auth()->user()->hasRole('superuser')) {
            $user->restore();
        } elseif (auth()->user()->hasRole('administrator')) {
            if (!$user->hasRole('superuser')) {
                $user->restore();
            }
        } elseif (auth()->user()->hasRole('staff')) {
            if (!$user->hasAnyRole(['superuser', 'administrator', 'staff'])) {
                $user->restore();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->route('users.trashed')
            ->with('status', 'User restored successfully.');
    }

    public function forceDelete($id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        // Prevent deletion of last superuser
        if ($user->hasRole('superuser')) {
            $superuserCount = User::role('superuser')->count();
            if ($superuserCount <= 1) {
                return redirect()->back()->withErrors(['user' => 'Cannot delete the last superuser.']);
            }
        }

        // Check permissions based on user role
        if (auth()->user()->hasRole('superuser')) {
            if ($user->id !== auth()->id()) {
                $user->forceDelete();
            }
        } elseif (auth()->user()->hasRole('administrator')) {
            if (!$user->hasRole('superuser')) {
                $user->forceDelete();
            }
        } elseif (auth()->user()->hasRole('staff')) {
            if (!$user->hasAnyRole(['superuser', 'administrator', 'staff'])) {
                $user->forceDelete();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }

        return redirect()->route('users.trashed')
            ->with('status', 'User permanently deleted.');
    }
}
