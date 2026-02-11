<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Create a new unique user for each profile
            'name' => fake()->name(),
            'major' => fake()->randomElement(['Teknik Elektro', 'Teknik Biomedis', 'Teknik Fisika', 'Teknik Telekomunikasi', 'Teknik Sistem Energi']),
            'class' => fake()->word(),
            'gender' => fake()->randomElement(['Male', 'Female']),
        ];
    }
}
