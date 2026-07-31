<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Tour;
use App\Models\User;
use App\Repositories\Services\Contracts\TourServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TourCancelledNotification;

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

    public function test_cancel_tour_notifies_walker(): void
    {
        Notification::fake();

        $tutor = User::factory()->create();
        $walker = User::factory()->create();

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => $walker->id,
            'status' => 'aceito'
        ]);

        Sanctum::actingAs($tutor);

        $response = $this->patchJson("/api/tours/{$tour->id}/cancel");

        $response->assertStatus(200);

        Notification::assertSentTo($walker, TourCancelledNotification::class);
    }
}