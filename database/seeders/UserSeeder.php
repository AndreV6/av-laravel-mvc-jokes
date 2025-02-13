<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superuser
        $superuserAccount = User::create([
            'given_name' => 'Super Admin',
            'family_name' => 'Test',
            'nickname' => 'Super',
            'email' => 'super@admin.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $superuserAccount->assignRole('superuser');

        // Create admin user
        $adminAccount = User::create([
            'given_name' => 'Admin User',
            'family_name' => 'Test',
            'nickname' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $adminAccount->assignRole('administrator');

        // Create staff user
        $staffAccount = User::create([
            'given_name' => 'Staff User',
            'family_name' => 'Test',
            'nickname' => 'Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $staffAccount->assignRole('staff');

        // Create client user
        $clientAccount = User::create([
            'given_name' => 'Client User',
            'family_name' => 'Test',
            'nickname' => 'Client',
            'email' => 'client@example.com',
            'password' => Hash::make('Password1'),
            'email_verified_at' => now(),
        ]);
        $clientAccount->assignRole('client');
    }
}
