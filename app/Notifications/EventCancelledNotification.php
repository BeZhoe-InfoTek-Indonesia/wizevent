<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventCancelledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public \App\Models\Event $event,
        public string $cancellationReason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Cancelled',
        );
    }

    public function build(): Mailable
    {
        return $this->view('emails.event-cancelled')
            ->with([
                'event' => $this->event,
                'reason' => $this->cancellationReason,
            ]);
    }
}
