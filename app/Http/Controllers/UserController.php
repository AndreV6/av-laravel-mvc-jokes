<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Controller handling all user management functionality including BREAD operations
 * (Browse, Read, Edit, Add, Delete) and user search capabilities
 */

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
     * Display a paginated list of users with search functionality
     *
     * Searches across name, email, and nickname fields
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $users = User::query()
            ->when($search, function($query, $search) {
                $query->where('given_name', 'like', "%{$search}%")
                    ->orWhere('family_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nickname', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Display detailed information for a specific user
     *
     * Users can only view themselves or users they created unless they are
     * a superuser or administrator
     *
     * @param User $user
     * @return View
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
     * Show form to edit a user's details
     *
     * Users can only edit themselves or users they created unless they are
     * a superuser or administrator
     *
     * @param User $user
     * @return View
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
     * Show form to create a new user
     *
     * @return View
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     *
     * Creates a new user with the provided information and assigns the
     * authenticated user as their creator
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'given_name' => ['required', 'string', 'max:255'],
            'family_name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'given_name' => $validated['given_name'],
            'family_name' => $validated['family_name'],
            'nickname' => $validated['nickname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('users.show', $user)
            ->with('status', 'User created successfully.');
    }

    /**
     * Update user details
     *
     * Users can only update themselves or users they created
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // User can only update themselves or users they created
        if ($user->id !== auth()->id() && $user->created_by !== auth()->id()) {
            abort(403, 'You can only update your own account or accounts you created.');
        }

        $validated = $request->validate([
            'given_name' => ['required', 'string', 'max:255'],
            'family_name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('status', 'User updated successfully.');
    }

    /**
     * Soft delete a user
     *
     * Prevents deletion of the last superuser. Users can be restored later
     * by authorized users.
     *
     * @param User $user
     * @return RedirectResponse
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

    /**
     * Display list of soft-deleted users
     *
     * @return View
     */
    public function trashed(): View
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('users.trashed', compact('users'));
    }

    /**
     * Restore a soft-deleted user
     *
     * Permissions are checked based on user roles to determine if restoration
     * is allowed
     *
     * @param int $id
     * @return RedirectResponse
     */
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

        /**
         * Permanently delete a user
         *
         * Checks role permissions and prevents deletion of the last superuser
         *
         * @param int $id
         * @return RedirectResponse
         */
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
