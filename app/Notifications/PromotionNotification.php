<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromotionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $title,
        public string $description,
        public ?string $link = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $title,
        );
    }

    public function build(): Mailable
    {
        return $this->view('emails.promotion')
            ->with([
                'title' => $this->title,
                'description' => $this->description,
                'link' => $this->link,
            ]);
    }
}
