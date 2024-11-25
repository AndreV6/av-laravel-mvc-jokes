<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JokeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $jokes = [
            [
                'joke' => "Why don't programmers like nature? It has too many bugs.",
                'category_id' => 1, // Programming
                'tags' => 'programming,bugs,nature',
                'author_id' => 1,
                'created_at' => now(),
            ],
            [
                'joke' => "What do you call a bear with no teeth? A gummy bear!",
                'category_id' => 2, // Dad Jokes
                'tags' => 'bears,food,dad joke',
                'author_id' => 1,
                'created_at' => now(),
            ],
            [
                'joke' => "What did the SQL query say when entering a bar? 'Can I JOIN you?'",
                'category_id' => 1, // Programming
                'tags' => 'programming,sql,database',
                'author_id' => 1,
                'created_at' => now(),
            ],
            [
                'joke' => "Knock knock! Who's there? Interrupting cow. Interrupting cow w- MOO!",
                'category_id' => 5, // Knock Knock
                'tags' => 'knock knock,animals,classic',
                'author_id' => 1,
                'created_at' => now(),
            ],
            [
                'joke' => "I'm reading a book about anti-gravity. It's impossible to put down!",
                'category_id' => 3, // Puns
                'tags' => 'science,books,puns',
                'author_id' => 1,
                'created_at' => now(),
            ],
        ];

        DB::table('jokes')->insert($jokes);
    }
}
