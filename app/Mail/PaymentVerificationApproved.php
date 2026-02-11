<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentVerificationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('email.payment_approved_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-verification-approved',
            with: [
                'order' => $this->order,
                'user' => $this->order->user,
                'event' => $this->order->event,
                'tickets' => $this->order->tickets,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
