<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        $tour = Tour::factory()->create();

        return [
            'passeio_id'     => $tour->id,
            'tutor_id'       => $tour->tutor_id,
            'passeador_id'   => $tour->passeador_id,
            'nota'           => fake()->numberBetween(1, 5),
            'comentario'     => fake()->sentence(),
            'tipo_avaliador' => fake()->randomElement(['tutor', 'passeador']),
        ];
    }
}