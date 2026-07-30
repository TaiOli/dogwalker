<?php

namespace Database\Factories;

use App\Enums\DogPorte;
use App\Models\Dog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dog>
 */
class DogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->firstName(),
            'porte' => fake()->randomElement(DogPorte::cases()), 
            'raca' => fake()->word(),
            'user_id' => User::factory(),
        ];
    }
}