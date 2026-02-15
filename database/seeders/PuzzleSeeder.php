<?php

namespace Database\Seeders;

use App\Models\Puzzle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuzzleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cores = [
            ['name' => 'Xurith',    'clue' => 'Look underneath',           'answer' => '1234'],
            ['name' => 'Thevia',    'clue' => 'The twilight star',          'answer' => '1234'],
            ['name' => 'Euprus',    'clue' => 'Shining its brilliance',     'answer' => '1234'],
            ['name' => 'Northgard', 'clue' => 'Within the night sky',       'answer' => '1234'],
        ];

        foreach ($cores as $core) {
            Puzzle::create($core);
        }
    }
}
