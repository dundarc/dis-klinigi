<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $title;
    public string $body;
    public string $type;
    public ?int $sender_id;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $body, string $type, ?int $sender_id = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->sender_id = $sender_id;
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
            'body' => $this->body,
            'type' => $this->type,
            'sender_id' => $this->sender_id,
        ];
    }
}