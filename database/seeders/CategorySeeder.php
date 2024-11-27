<?php

/**
 * Assessment Title: Portfolio Part 3
 * Cluster: SaaS: Fron-End Dev - ICT50220 (Advanced Programming)
 * Qualification: ICT50220 Diploma of Information Technology (Advanced Programming)
 * Name: Andre Velevski
 * Student ID: 20094240
 * Year/Semester: 2024/S2
 *
 * Seeder for creating initial joke categories
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Create default joke categories
     *
     * Creates categories:
     * - Programming
     * - Dad Jokes
     * - Puns
     * - One Liners
     * - Knock Knock
     *
     * @return void
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Programming',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Dad Jokes',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Puns',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'One Liners',
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Knock Knock',
                'user_id' => 1,
                'created_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
