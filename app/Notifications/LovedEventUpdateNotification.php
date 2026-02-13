<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LovedEventUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public \App\Models\Event $event,
        public string $changes
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Loved Event Update',
        );
    }

    public function build(): Mailable
    {
        return $this->view('emails.loved-event-update')
            ->with([
                'event' => $this->event,
                'changes' => $this->changes,
            ]);
    }
}
