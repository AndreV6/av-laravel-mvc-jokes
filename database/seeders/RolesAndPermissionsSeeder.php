<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Seeder for creating roles and permissions system
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Create roles, permissions, and initial users
     *
     * Creates:
     * 1. Basic permissions for jokes and users
     * 2. Roles: superuser, administrator, staff, client
     * 3. Initial users for each role
     * 4. Assigns appropriate permissions to each role
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'joke.browse']);
        Permission::create(['name' => 'joke.read']);
        Permission::create(['name' => 'joke.add']);
        Permission::create(['name' => 'joke.edit']);
        Permission::create(['name' => 'joke.delete']);
        Permission::create(['name' => 'joke.restore']);
        Permission::create(['name' => 'joke.force-delete']);

        Permission::create(['name' => 'user.browse']);
        Permission::create(['name' => 'user.read']);
        Permission::create(['name' => 'user.add']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.delete']);
        Permission::create(['name' => 'user.register']);
        Permission::create(['name' => 'user.login']);
        Permission::create(['name' => 'user.logout']);
        Permission::create(['name' => 'user.restore']);
        Permission::create(['name' => 'user.force-delete']);

        // Create roles and assign permissions
        $superuser = Role::create(['name' => 'superuser']);
        $superuser->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'administrator']);
        $admin->givePermissionTo([
            'joke.browse', 'joke.read', 'joke.add', 'joke.edit', 'joke.delete', 'joke.force-delete', 'joke.restore',
            'user.browse', 'user.read', 'user.add', 'user.edit', 'user.delete', 'user.logout', 'user.force-delete', 'user.restore'
        ]);

        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'joke.browse', 'joke.read', 'joke.add', 'joke.edit', 'joke.delete', 'joke.force-delete', 'joke.restore',
            'user.browse', 'user.read', 'user.logout', 'user.force-delete', 'user.restore'
        ]);

        $client = Role::create(['name' => 'client']);
        $client->givePermissionTo([
            'joke.browse', 'joke.read', 'joke.add', 'joke.edit', 'joke.delete', 'joke.force-delete', 'joke.restore',
            'user.browse', 'user.read', 'user.logout'
        ]);


    }
}
