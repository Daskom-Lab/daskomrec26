<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $stages = [
        ['name' => 'Administration'],
        ['name' => 'Coding and Writing Test'],
        ['name' => 'Interview'],
        ['name' => 'Group Task'],
        ['name' => 'Teaching Test'],
        ['name' => 'Rising'],
    ];
    
    foreach ($stages as $stage) {
        Stage::create($stage);
    }
}
}
