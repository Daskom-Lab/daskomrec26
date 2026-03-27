<?php

namespace Database\Seeders;

use App\Models\Plottingan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlottinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::has('profile')->get();
        $shifts = \App\Models\Shift::all();

        if ($users->isEmpty() || $shifts->isEmpty()) {
            return;
        }

        Plottingan::truncate();

        // generate diverse plottingans (user-shift assignments)
        foreach (range(1, 15) as $_) {
            Plottingan::create([
                'user_id' => $users->random()->id,
                'shift_id' => $shifts->random()->id,
            ]);
        }
    }
}
