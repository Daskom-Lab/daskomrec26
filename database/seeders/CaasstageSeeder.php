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
        $administrationStage = Stage::where('name', 'Administration')->first();

        if ($users->isEmpty() || !$administrationStage) {
            return;
        }

        foreach ($users as $user) {
            // Only create if the user doesn't already have a CaasStage
            if (!$user->caasStage) {
                CaasStage::create([
                    'user_id' => $user->id,
                    'stage_id' => $administrationStage->id,
                    'status' => 'GAGAL', // Default status
                ]);
            }
        }
    }
}
