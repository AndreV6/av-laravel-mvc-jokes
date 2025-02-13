<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Main database seeder coordinating all seed operations
 */

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database
     *
     * Runs seeders in the correct order:
     * 1. Roles and permissions
     * 2. Test user
     * 3. Categories
     * 4. Jokes
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            JokeSeeder::class,
        ]);
    }
}
