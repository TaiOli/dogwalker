<?php

namespace App\Notifications;

use App\Models\Tour;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TourCancelledNotification extends Notification
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
        // Envia por e-mail apenas se o usuário tiver o e-mail verificado
        if ($notifiable->email_verified_at) {
            return ['mail', 'database'];
        }

        // Caso contrário, envia apenas para o banco de dados
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Um passeio foi cancelado')
            ->greeting("Olá, {$notifiable->nome}!")
            ->line("O passeio do cachorro {$this->tour->dog->nome} marcado para {$this->tour->data} às {$this->tour->hora}, foi cancelado pelo tutor")
            ->action('Ver passeios disponíveis', url('app.frontend_url') . '/passeios')
            ->line('Sentimos muito pelo inconveniente!');
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
            'message' => "Olá {$notifiable->nome}, o passeio de {$this->tour->dog->nome} foi cancelado pelo tutor."
        ];
    }
}