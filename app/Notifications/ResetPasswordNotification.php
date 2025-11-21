<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notificación personalizada para restablecimiento de contraseña.
 *
 * Envía un correo electrónico al usuario con un enlace para restablecer su contraseña.
 * Todos los textos están en español.
 */
class ResetPasswordNotification extends Notification
{
    /**
     * Token de restablecimiento de contraseña.
     */
    protected string $token;

    /**
     * Crear una nueva instancia de la notificación.
     *
     * @param  string  $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Obtener los canales de entrega de la notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Obtener la representación de correo de la notificación.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expireMinutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject('Restablecer Contraseña - Agua Colegial')
            ->greeting('¡Hola!')
            ->line('Has recibido este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace de restablecimiento expirará en '.$expireMinutes.' minutos.')
            ->line('Si no solicitaste restablecer tu contraseña, no es necesario realizar ninguna acción.')
            ->salutation('Saludos, Equipo de Agua Colegial');
    }
}
