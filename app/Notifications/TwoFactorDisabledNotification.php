<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorDisabledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Verificación en dos pasos desactivada')
            ->greeting("Hola, {$notifiable->name}")
            ->line('La verificación en dos pasos ha sido **desactivada** en tu cuenta.')
            ->line('Tu cuenta ahora solo está protegida por tu contraseña.')
            ->line('Si no fuiste tú quien realizó este cambio, cambia tu contraseña de inmediato.')
            ->action('Reactivar 2FA', url('/user/two-factor-authentication'))
            ->error() // pone el botón en rojo — señal de alerta
            ->salutation('El equipo de ' . config('app.name'));
    }
}
