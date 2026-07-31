<?php

namespace App\Notifications;

use App\Models\Tour;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TourAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Tour $tour
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Seu passeio foi aceito!')
            ->greeting("Olá, {$notifiable->nome}!")
            ->line("O passeador {$this->tour->walker->nome} aceitou o passeio do seu cachorro {$this->tour->dog->nome}.")
            ->line("Data: {$this->tour->data} às {$this->tour->hora}")
            ->action('Ver detalhes', config('app.frontend_url') . '/passeios')
            ->line('Obrigado por usar o DogWalker!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tour_id' => $this->tour->id,
            'message' => 'Olá ' . $notifiable->name . ', seu passeio foi aceito por ' . $this->tour->walker->nome . '!'
        ];
    }
}