<?php

namespace App\Notifications;

use App\Models\Evaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvaluationReceivedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Evaluation $evaluation) {}

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
            ->subject('Você recebeu uma nova avaliação!')
            ->greeting("Olá, {$notifiable->nome}!")
            ->line("Você recebeu uma avaliação de {$this->evaluation->nota}/5.")
            ->when($this->evaluation->comentario, function (MailMessage $mail) {
                return $mail->line("Comentário: \"{$this->evaluation->comentario}\"");
            })
            ->action('Ver perfil', config('app.frontend_url') . '/meu-perfil')
            ->line('Continue oferecendo um ótimo serviço!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'evaluation_id' =>$this->evaluation->id,
            'message' => 'Olá ' . $notifiable->nome . ', você recebeu uma avaliação de ' . $this->evaluation->nota . '/5!'
        ];
    }
}