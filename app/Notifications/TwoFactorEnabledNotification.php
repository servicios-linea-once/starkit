<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorEnabledNotification extends Notification  implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Verificación en dos pasos activada')
            ->greeting("Hola, {$notifiable->name}")
            ->line('La verificación en dos pasos ha sido **activada** en tu cuenta.')
            ->line('A partir de ahora necesitarás tu autenticador cada vez que inicies sesión.')
            ->line('Si no fuiste tú quien realizó este cambio, contacta soporte de inmediato.')
            ->action('Ver mi cuenta', url('/settings/profile'))
            ->line('Gracias por mantener tu cuenta segura.')
            ->salutation('El equipo de ' . config('app.name'));
    }
}
