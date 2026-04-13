<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $ip,
        protected string $userAgent
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nuevo inicio de sesión — StarKit')
            ->view('emails.login-notification', [
                'user'      => $notifiable,
                'ip'        => $this->ip,
                'userAgent' => $this->userAgent,
                'time'      => now()->format('d/m/Y H:i'),
            ]);
    }
}
