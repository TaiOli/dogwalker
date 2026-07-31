<?php

namespace Tests\Unit\Notifications;

use Tests\TestCase;
use App\Models\Tour;
use App\Models\User;
use App\Models\Dog;
use App\Notifications\TourCancelledNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TourCancelledNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_has_correct_channels(): void
    {
        $tour = Tour::factory()->create();
        $notification = new TourCancelledNotification($tour);

        $channels = $notification->via($tour->walker ?? $tour->tutor);

        $this->assertContains('mail', $channels);
        $this->assertContains('database', $channels);
    }

    public function test_mail_message_contains_dog_name_and_datetime(): void
    {
        $walker = User::factory()->create();
        $dog = Dog::factory()->create();

        $tour = Tour::factory()->create([
            'passeador_id' => $walker->id,
            'dog_id' => $dog->id,
            'data' => '2026-08-15',
            'hora' => '10:00:00',
        ]);

        $notification = new TourCancelledNotification($tour);
        $mail = $notification->toMail($walker);

        $this->assertStringContainsString($dog->nome, $mail->render());
    }

    public function test_array_representation_has_expected_keys(): void
    {
        $tour = Tour::factory()->create();
        $notification = new TourCancelledNotification($tour);

        $array = $notification->toArray($tour->tutor);

        $this->assertArrayHasKey('tour_id', $array);
        $this->assertArrayHasKey('message', $array);
    }
}