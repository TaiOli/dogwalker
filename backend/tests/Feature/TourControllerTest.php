<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tour;
use App\Repositories\Services\Contracts\TourServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TourControllerTest extends TestCase {

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
}
