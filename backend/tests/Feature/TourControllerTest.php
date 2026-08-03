<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use App\Enums\TipoUsuario;
use Tests\TestCase;
use App\Models\Tour;
use App\Models\User;
use App\Repositories\Services\Contracts\TourServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TourControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $tourServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tourServiceMock = $this->mock(TourServiceInterface::class);
    }

    public function create_tour_test()
    {
        $dogData = ['name' => 'Rex', 'breed' => 'Labrador'];
        $createdDog = new Tour($dogData);
        $createdDog->id = 1;

        $this->tourServiceMock
            ->shouldReceive('create')
            ->once()
            ->andReturn($createdDog);

        $response = $this->postJson('/api/tours', $dogData);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Passeio cadastrado com sucesso!']);
    }

    public function test_accept_tour_returns_200(): void
    {
        $tutor = User::factory()->create();
        $walker = User::factory()->create([
            'tipo_usuario' => TipoUsuario::PASSEADOR->value,
        ]);

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => null,
            'status' => 'pendente',
        ]);

        $acceptedTour = clone $tour;
        $acceptedTour->passeador_id = $walker->id;
        $acceptedTour->status = 'aceito';

        $this->tourServiceMock
            ->shouldReceive('accept')
            ->once()
            ->with($tour->id, $walker->id)
            ->andReturn($acceptedTour);

        Sanctum::actingAs($walker);

        $response = $this->putJson("/api/tours/{$tour->id}/accept");

        $response->assertStatus(200);
    }
}