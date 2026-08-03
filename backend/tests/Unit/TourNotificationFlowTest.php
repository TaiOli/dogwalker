<?php

namespace Tests\Feature;

use Laravel\Sanctum\Sanctum;
use App\Enums\TipoUsuario;
use Tests\TestCase;
use App\Models\Tour;
use App\Models\User;
use App\Notifications\TourAcceptedNotification;
use App\Notifications\TourCancelledNotification;
use App\Notifications\TourCompletedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class TourNotificationFlowTest extends TestCase
{
    use RefreshDatabase;

    // Nenhum mock de Service aqui — o TourService real roda por trás

    public function test_accept_tour_notifies_tutor(): void
    {
        Notification::fake();

        $tutor = User::factory()->create();
        $walker = User::factory()->create([
            'tipo_usuario' => TipoUsuario::PASSEADOR->value,
        ]);

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => null,
            'status' => 'pendente',
        ]);

        Sanctum::actingAs($walker);

        $response = $this->putJson("/api/tours/{$tour->id}/accept");

        $response->assertStatus(200);

        Notification::assertSentTo($tutor, TourAcceptedNotification::class);
    }

    public function test_cancel_tour_notifies_walker(): void
    {
        Notification::fake();

        $tutor = User::factory()->create();
        $walker = User::factory()->create([
            'tipo_usuario' => TipoUsuario::PASSEADOR->value,
        ]);

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => $walker->id,
            'status' => 'aceito',
        ]);

        Sanctum::actingAs($tutor);

        $response = $this->patchJson("/api/tours/{$tour->id}/cancel");

        $response->assertStatus(200);

        Notification::assertSentTo($walker, TourCancelledNotification::class);
    }

    public function test_cancel_pending_tour_without_walker_does_not_notify(): void
    {
        Notification::fake();

        $tutor = User::factory()->create();

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => null,
            'status' => 'pendente',
        ]);

        Sanctum::actingAs($tutor);

        $response = $this->patchJson("/api/tours/{$tour->id}/cancel");

        $response->assertStatus(200);

        Notification::assertNothingSent();
    }

    public function test_complete_tour_notifies_tutor(): void
    {
        Notification::fake();

        $tutor = User::factory()->create();
        $walker = User::factory()->create([
            'tipo_usuario' => TipoUsuario::PASSEADOR->value,
        ]);

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => $walker->id,
            'status' => 'aceito',
        ]);

        Sanctum::actingAs($walker);

        $response = $this->patchJson("/api/tours/{$tour->id}/complete");

        $response->assertStatus(200);

        Notification::assertSentTo($tutor, TourCompletedNotification::class);
    }
}