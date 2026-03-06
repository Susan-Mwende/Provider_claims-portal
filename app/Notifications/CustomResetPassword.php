<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class CustomResetPassword extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Start from configured app URL, defaulting to http and 127.0.0.1 if needed
        $base = rtrim(config('app.url') ?: 'http://127.0.0.1:8000', '/');
        // Force http scheme in case config has https
        $base = preg_replace('/^https:/i', 'http:', $base);
        // Prefer 127.0.0.1 over localhost to avoid any client-side HTTPS upgrades on localhost
        $base = str_ireplace('https://localhost', 'http://127.0.0.1', $base);
        $base = str_ireplace('http://localhost', 'http://127.0.0.1', $base);

        $relative = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false);

        $resetUrl = $base . $relative; // Final HTTP URL
        Log::info('Password reset URL generated', ['url' => $resetUrl]);

        return (new MailMessage)
            ->subject(__('Reset Password Notification'))
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->action(__('Reset Password'), $resetUrl)
            ->line(__('If you did not request a password reset, no further action is required.'));
    }
}
