<?php

namespace Tests\Unit\Notifications;

use App\Models\Dog;
use App\Models\Tour;
use App\Models\User;
use App\Notifications\TourAcceptedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourAcceptedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_correct_channels(): void
    {
        $tour = Tour::factory()->create();
        $notification = new TourAcceptedNotification($tour);

        $channels = $notification->via($tour->tutor);

        $this->assertContains('mail', $channels);
        $this->assertContains('database', $channels);
    }

    public function test_mail_message_contains_walker_and_dog_name(): void
    {
        $tutor = User::factory()->create();
        $walker = User::factory()->create();
        $dog = Dog::factory()->create(['user_id' => $tutor->id]);

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => $walker->id,
            'dog_id' => $dog->id
        ]);

        $notification = new TourAcceptedNotification($tour);
        $mail = $notification->toMail($tutor);

        $this->assertStringContainsString($dog->nome, $mail->render());
        $this->assertStringContainsString($walker->nome, $mail->render());
    }

    public function test_array_representation_expected_keys(): void
    {
        $tutor = User::factory()->create();
        $walker = User::factory()->create();

        $dog = Dog::factory()->create([
            'user_id' => $tutor->id,
        ]);

        $tour = Tour::factory()->create([
            'tutor_id' => $tutor->id,
            'passeador_id' => $walker->id,
            'dog_id' => $dog->id,
        ]);

        $notification = new TourAcceptedNotification($tour);

        $array = $notification->toArray($tutor);

        $this->assertArrayHasKey('tour_id', $array);
        $this->assertArrayHasKey('message', $array);
        $this->assertEquals($tour->id, $array['tour_id']);
    }
}