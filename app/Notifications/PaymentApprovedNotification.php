<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Approved — Your Tickets Are Ready!',
        );
    }

    public function build(): Mailable
    {
        return $this->view('emails.payment-approved')
            ->with([
                'order' => $this->order,
            ]);
    }
}
