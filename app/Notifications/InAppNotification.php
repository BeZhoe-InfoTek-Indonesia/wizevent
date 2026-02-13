<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification as BaseNotification;

class InAppNotification extends BaseNotification
{
    use Queueable;

    public function __construct(
        public string $type,
        public array $data = []
    ) {
        $this->data = array_merge($data, [
            'type' => $type,
        ]);
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toMail($notifiable): void
    {
        // In-app notifications don't send email

    }

    public function toArray($notifiable): array
    {
        return [
            'id' => $this->id,
            'type' => $this->data['type'] ?? 'notification',
            'title' => $this->data['title'] ?? 'Notification',
            'message' => $this->data['message'] ?? '',
            'data' => $this->data,
        ];
    }
}
