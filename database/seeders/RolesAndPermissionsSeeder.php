<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
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

        Permission::create(['name' => 'user.browse']);
        Permission::create(['name' => 'user.read']);
        Permission::create(['name' => 'user.add']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.delete']);
        Permission::create(['name' => 'user.register']);
        Permission::create(['name' => 'user.login']);
        Permission::create(['name' => 'user.logout']);

        // Create roles and assign permissions
        $superuser = Role::create(['name' => 'superuser']);
        $superuser->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'administrator']);
        $admin->givePermissionTo([
            'joke.browse', 'joke.read', 'joke.add', 'joke.edit', 'joke.delete',
            'user.browse', 'user.read', 'user.add', 'user.edit', 'user.delete', 'user.logout'
        ]);

        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'joke.browse', 'joke.read', 'joke.add', 'joke.edit', 'joke.delete',
            'user.browse', 'user.read', 'user.logout'
        ]);

        $client = Role::create(['name' => 'client']);
        $client->givePermissionTo([
            'joke.browse', 'joke.read', 'joke.add',
            'user.browse', 'user.read', 'user.logout'
        ]);

        // Create superuser
        $superuserAccount = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $superuserAccount->assignRole('superuser');

        // Create admin user
        $adminAccount = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $adminAccount->assignRole('administrator');

        // Create staff user
        $staffAccount = User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $staffAccount->assignRole('staff');

        // Create client user
        $clientAccount = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $clientAccount->assignRole('client');
    }
}
