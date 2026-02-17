<?php

namespace Database\Seeders;

use App\Models\CaasStage;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaasStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $stages = Stage::all();

        if ($users->isEmpty() || $stages->isEmpty()) {
            return;
        }

        $statuses = ['GAGAL', 'LOLOS', 'PROSES'];

        foreach ($users as $user) {
            CaasStage::create([
                'user_id' => $user->id,
                'stage_id' => $stages->random()->id,
                'status' => fake()->randomElement($statuses),
            ]);
        }
    }
}
