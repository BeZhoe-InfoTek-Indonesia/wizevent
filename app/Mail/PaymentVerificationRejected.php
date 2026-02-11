<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentVerificationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public string $rejectionReason;

    public function __construct(Order $order, string $rejectionReason)
    {
        $this->order = $order;
        $this->rejectionReason = $rejectionReason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('email.payment_rejected_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-verification-rejected',
            with: [
                'order' => $this->order,
                'user' => $this->order->user,
                'event' => $this->order->event,
                'rejectionReason' => $this->rejectionReason,
            ],
        );
    }
}
