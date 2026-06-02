<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    protected $ip;
    protected $location;
    protected $userAgent;

    /**
     * Create a new notification instance.
     */
    public function __construct($ip, $location, $userAgent)
    {
        $this->ip = $ip;
        $this->location = $location;
        $this->userAgent = $userAgent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Parsing user agent to be more human readable if possible, or just fallback
        $browser = $this->userAgent;
        if (preg_match('/MSIE/i', $this->userAgent) && !preg_match('/Opera/i', $this->userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $this->userAgent)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $this->userAgent)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $this->userAgent)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/Opera/i', $this->userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Netscape/i', $this->userAgent)) {
            $browser = 'Netscape';
        }

        $time = now()->timezone('Asia/Jakarta')->format('d M Y H:i:s') . ' WIB';

        return (new MailMessage)
            ->subject('Keamanan Akun: Password SemestaPro Berhasil Diubah')
            ->markdown('emails.password-changed', [
                'name' => $notifiable->name,
                'time' => $time,
                'ip' => $this->ip,
                'location' => $this->location,
                'browser' => $browser
            ]);
    }
}
