<?php

namespace Database\Seeders;

use App\Models\Quest;
use App\Models\Question;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $quest = Quest::query()->first();
        for ($i = 0; $i < 100; $i++) {
            Question::query()->create([
                'quest_id' => $quest->id,
                'title' => 'test',
                'description' => 'test',
                'type' => 'single_answer',
                'single_answer' => 'test',
            ]);
        }
    }
}
