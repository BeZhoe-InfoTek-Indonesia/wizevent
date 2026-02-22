<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestimonialReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Event $event
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('testimonial.reminder_email_subject'))
            ->greeting(__('email.greeting', ['name' => $notifiable->name]))
            ->line(__('testimonial.reminder_email_line1', ['event' => $this->event->title]))
            ->line(__('testimonial.reminder_email_line2'))
            ->action(__('testimonial.reminder_email_button'), route('events.review', ['slug' => $this->event->slug]))
            ->line(__('testimonial.reminder_email_line3'))
            ->salutation(__('email.salutation'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_slug' => $this->event->slug,
        ];
    }
}
