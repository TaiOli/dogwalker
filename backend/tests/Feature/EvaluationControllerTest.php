<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_rating(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $tour = Tour::factory()->create([
            'tutor_id' => $user->id,
            'status' => 'finalizado',
        ]);

        $payload = [
            'passeio_id'     => $tour->id,
            'tipo_avaliador' => 'tutor',
            'nota'           => 5,
            'comentario'     => 'Ótimo passeio!',
        ];

        $response = $this->postJson('/api/evaluation', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'message' => 'Avaliação enviada com sucesso!'
            ])
            ->assertJsonStructure([
                'message',
                'avaliacao' => [
                    'id',
                    'nota',
                    'comentario'
                ]
            ]);

        $this->assertDatabaseHas('avaliacoes', [
            'passeio_id' => $tour->id,
            'nota'       => 5,
        ]);
    }

    public function test_unauthenticated_save_evaluation(): void
    {
        // Usuário não logado deve receber 401 Unauthorized
        $payload = [
            'passeio_id'     => 1,
            'tipo_avaliador' => 'cliente',
            'nota'           => 5,
        ];

        $response = $this->postJson('/api/evaluation', $payload);

        $response->assertStatus(401);
    }
}