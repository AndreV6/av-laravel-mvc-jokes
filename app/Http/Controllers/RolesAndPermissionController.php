<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Controller for managing user roles and permissions
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class RolesAndPermissionController extends Controller
{

    /**
     * Show form to assign roles and permissions to a user
     *
     * @param User $user
     * @return View
     */
    public function assignRole(User $user): View
    {
        // Get all roles except superuser (if current user is not superuser)
        $roles = Role::when(!auth()->user()->hasRole('superuser'), function($query) {
            return $query->where('name', '!=', 'superuser');
        })->get();

        // Get all permissions and group them
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
        return view('admin.roles.assign', compact('user', 'roles', 'groupedPermissions'));
    }

    /**
     * Update user's roles
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updateUserRoles(Request $request, User $user): RedirectResponse
    {
        if ($user->hasRole('superuser') && !auth()->user()->hasRole('superuser')) {
            abort(403, 'Only superusers can modify superuser roles.');
        }

        $validated = $request->validate([
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $roles = Role::whereIn('id', $validated['roles'])->get();

        // Prevent removing superuser role if it's the last superuser
        if ($user->hasRole('superuser') && !in_array('superuser', $roles->pluck('name')->toArray())) {
            $superusers = User::role('superuser')->count();
            if ($superusers <= 1) {
                return redirect()->back()->withErrors(['roles' => 'Cannot remove the last superuser role.']);
            }
        }

        $user->syncRoles($roles);

        return redirect()->route('users.edit', $user)
            ->with('status', 'User roles updated successfully.');
    }

    /**
     * Update user's direct permissions
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updateUserPermissions(Request $request, User $user): RedirectResponse
    {
        if ($user->hasRole('superuser') && !auth()->user()->hasRole('superuser')) {
            abort(403, 'Only superusers can modify superuser permissions.');
        }

        $permissionIds = $request->input('permissions', []);

        if (empty($permissionIds)) {
            $user->syncPermissions([]);
        } else {
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $user->syncPermissions($permissions);
        }

        return redirect()->route('users.edit', $user)
            ->with('status', 'User permissions updated successfully.');
    }
}
