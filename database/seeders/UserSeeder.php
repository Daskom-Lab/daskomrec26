<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stage;
use App\Models\CaasStage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Administration stage
        $administrationStage = Stage::where('name', 'Administration')->first();
        
        // Create 10 regular users
        $users = User::factory()->count(10)->create();
        
        // Create CaasStage for each user with Administration stage
        if ($administrationStage) {
            foreach ($users as $user) {
                CaasStage::create([
                    'user_id' => $user->id,
                    'stage_id' => $administrationStage->id,
                    'status' => 'GAGAL', // Default status
                ]);
            }
        }
        
        // Create 1 admin user
        $adminUser = User::factory()->admin()->create();
        
        // Create CaasStage for admin user too
        if ($administrationStage && $adminUser) {
            CaasStage::create([
                'user_id' => $adminUser->id,
                'stage_id' => $administrationStage->id,
                'status' => 'GAGAL',
            ]);
        }
    }
}
