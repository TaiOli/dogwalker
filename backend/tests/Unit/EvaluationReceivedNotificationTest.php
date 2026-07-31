<?php

namespace Tests\Unit\Notifications;

use Tests\TestCase;
use App\Models\Evaluation;
use App\Models\Tour;
use App\Models\User;
use App\Notifications\EvaluationReceivedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationReceivedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_has_correct_channels(): void
    {
        $tour = Tour::factory()->create();

        $evaluation = Evaluation::factory()->create([
            'passeio_id'    => $tour->id,
            'tutor_id'      => $tour->tutor_id,
            'passeador_id'  => $tour->passeador_id,
            'nota'          => 5,
            'comentario'    => 'Excelente!',
            'tipo_avaliador' => 'tutor',
        ]);

        $notification = new EvaluationReceivedNotification($evaluation);

        $channels = $notification->via($tour->tutor);

        $this->assertContains('mail', $channels);
        $this->assertContains('database', $channels);
    }

    public function test_mail_message_contains_rating(): void
    {
        $recipient = User::factory()->create();
        $tour = Tour::factory()->create();

        $evaluation = Evaluation::factory()->create([
            'passeio_id'    => $tour->id,
            'tutor_id'      => $tour->tutor_id,
            'passeador_id'  => $tour->passeador_id,
            'nota'          => 5,
            'comentario'    => 'Excelente!',
            'tipo_avaliador' => 'tutor',
        ]);

        $notification = new EvaluationReceivedNotification($evaluation);
        $mail = $notification->toMail($recipient);

        $this->assertStringContainsString('5', $mail->render());
        $this->assertStringContainsString('Excelente!', $mail->render());
    }

    public function test_array_representation_has_expected_keys(): void
    {
        $tour = Tour::factory()->create();

        $evaluation = Evaluation::factory()->create([
            'passeio_id' => $tour->id,
            'nota' => 4,
        ]);

        $notification = new EvaluationReceivedNotification($evaluation);
        $array = $notification->toArray($tour->tutor);

        $this->assertArrayHasKey('evaluation_id', $array);
        $this->assertArrayHasKey('message', $array);
        $this->assertEquals($evaluation->id, $array['evaluation_id']);
    }
}