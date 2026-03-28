<?php

namespace Database\Seeders;

use App\Models\CaasStage;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = Stage::all();
        if ($stages->isEmpty()) {
            return; // stages needed for CaasStage assignment
        }

        $administrationStage = $stages->firstWhere('name', 'Administration') ?? $stages->first();
        $statusPool = ['PROSES', 'LOLOS', 'GAGAL'];

        // Create regular users with profiles and caas stages
        $users = User::factory()->count(20)->create();
        foreach ($users as $user) {
            $user->profile()->create([
                'name' => fake()->name(),
                'major' => fake()->randomElement([
                    'Teknik Elektro',
                    'Teknik Biomedis',
                    'Teknik Fisika',
                    'Teknik Telekomunikasi',
                    'Teknik Sistem Energi',
                ]),
                'class' => strtoupper(fake()->bothify('??-##')),
                'gender' => fake()->randomElement(['Male', 'Female']),
            ]);

            CaasStage::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'stage_id' => $administrationStage->id,
                'status' => fake()->randomElement($statusPool),
            ]);
        }

        // Create admin user with profile and caas stage
        $adminUser = User::firstOrCreate(
            ['nim' => '10101'],
            [
                'password' => Hash::make('password'),
                'is_admin' => true,
                'last_activity' => now()->timestamp,
            ]
        );

        $adminUser->profile()->updateOrCreate(
            ['user_id' => $adminUser->id],
            [
                'name' => 'Admin Daskom',
                'major' => 'Teknik Elektro',
                'class' => 'ADM-01',
                'gender' => 'Male',
            ]
        );

        CaasStage::firstOrCreate([
            'user_id' => $adminUser->id,
        ], [
            'stage_id' => $administrationStage->id,
            'status' => 'LOLOS',
        ]);
    }
}
