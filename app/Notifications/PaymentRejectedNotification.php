<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRejectedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public \App\Models\Order $order,
        public string $rejectionReason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Rejected',
        );
    }

    public function build(): Mailable
    {
        return $this->view('emails.payment-rejected')
            ->with([
                'order' => $this->order,
                'reason' => $this->rejectionReason,
            ]);
    }
}
