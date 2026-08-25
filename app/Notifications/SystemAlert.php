<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemAlert extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $icon;
    public $action_url;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $icon = '🔔', $action_url = '#')
    {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
        $this->action_url = $action_url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'action_url' => $this->action_url,
        ];
    }
}
