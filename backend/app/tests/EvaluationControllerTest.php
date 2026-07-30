<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluationTest extends TestCase
{
    use RefreshDatabase;

    public function save_rating_test(): void
    {
        // Cria um usuário válido e autenticá-lo via Sanctum
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // Cria um passeio que será avaliado
        $tour = Tour::factory()->create();

        // Dados que StoreEvaluationRequest espera
        $payload = [
            'passeio_id'     => $tour->id,
            'tipo_avaliador' => 'cliente',
            'nota'           => 5,
            'comentario'     => 'Ótimo passeio!',
        ];

        // Dispara a requisição POST para a URL
        $response = $this->postJson('/api/evaluation', $payload);

        // Valida o retorno HTTP 201 
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

        // Confirma que o registro foi gravado na tabela correta do banco de dados
        $this->assertDatabaseHas('evaluations', [
            'tour_id' => $tour->id,
            'nota'    => 5,
        ]);
    }

    public function unauthenticated_test_save_evaluation (): void
    {
        // Testar a segurança: Usuário não logado deve receber 401 Unauthorized
        $payload = [
            'passeio_id'     => 1,
            'tipo_avaliador' => 'cliente',
            'nota'           => 5,
        ];

        $response = $this->postJson('/api/evaluation', $payload);

        $response->assertStatus(401);
    }
}