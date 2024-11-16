<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
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
