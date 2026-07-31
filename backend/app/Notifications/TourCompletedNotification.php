<?php

namespace App\Notifications;

use App\Models\Tour;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TourCompletedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Tour $tour) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Seu passeio foi finalizado!')
            ->greeting("Olá, {$notifiable->nome}!")
            ->line("O passeio do seu cachorro {$this->tour->dog->nome} com {$this->tour->walker->nome}foi finalizado")
            ->line('Que tal avaliar como foi sua experiência?')
            ->action('Avaliar Passeio', url('app.frontend_url') . '/passeios')
            ->line('Obrigado por usar o DogWalker!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tour_id' => $this->tour->id,
            'message' => "Olá {$notifiable->name}, Passeio com {$this->tour->walker->nome} finalizado. Avalie sua experiência!"

        ];
    }
}