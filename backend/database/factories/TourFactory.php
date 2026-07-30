<?php

namespace Database\Factories;

use App\Models\Dog;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dog_id'       => Dog::factory(),
            'tutor_id'     => User::factory(),
            'passeador_id' => null,
            'data'         => fake()->date(),
            'hora'         => fake()->time('H:i:s'),
            'duracao'      => '01:00:00',
            'local'        => fake()->address(),
            'valor'        => fake()->randomFloat(2, 20, 100),
            'status'       => 'pendente',
        ];
    }
}
