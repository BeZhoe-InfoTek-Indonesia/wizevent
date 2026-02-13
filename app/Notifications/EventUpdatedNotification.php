<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventUpdatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public \App\Models\Event $event,
        public string $changes
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Updated',
        );
    }

    public function build(): Mailable
    {
        return $this->view('emails.event-updated')
            ->with([
                'event' => $this->event,
                'changes' => $this->changes,
            ]);
    }
}
