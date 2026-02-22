<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PaymentPendingReviewNotification extends Notification
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment proof uploaded — Order '.$this->order->order_number)
            ->greeting('Hello')
            ->line('A user uploaded a payment proof for Order #'.$this->order->order_number)
            ->action('Review Payment', url(route('filament.resources.orders.view', $this->order->id) ?: '/admin'))
            ->line('Please review and verify the payment.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => 'Payment proof uploaded and awaiting review',
        ];
    }
}
