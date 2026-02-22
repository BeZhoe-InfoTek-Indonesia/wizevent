<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use App\Models\Order;

class OrderEmailTest extends TestCase
{
    public function test_order_created_mailable_is_sent()
    {
        Mail::fake();

        // Build model instances in-memory (avoid DB/migrations) and attach relations
        $user = new \App\Models\User([
            'name' => 'John Visitor',
            'email' => 'john@example.test',
        ]);

        $event = new \App\Models\Event([
            'title' => 'The Grand Illusion Magic Show',
            'venue_name' => 'Grand Theater, Hall A',
            'event_date' => now(),
        ]);

        $order = new Order([
            'uuid' => (string) \Illuminate\Support\Str::ulid(),
            'order_number' => 'ORD-2023-8901',
            'total_amount' => 145.00,
        ]);

        // Assign an id so route generation in the view works
        $order->id = 1;

        // Create order items and ticket types in-memory
        $ticketType = new \App\Models\TicketType(['name' => 'VIP Ticket']);
        $item = new \App\Models\OrderItem(['quantity' => 2, 'total_price' => 145.00]);
        $item->setRelation('ticketType', $ticketType);

        $order->setRelation('user', $user);
        $order->setRelation('event', $event);
        $order->setRelation('orderItems', collect([$item]));

        // Send the mailable just like application code would
        Mail::to($user->email)->send(new OrderCreated($order));

        // Assert the mailable was sent and contains expected data
        Mail::assertSent(OrderCreated::class, function (OrderCreated $mail) use ($order) {
            // Check recipient and that the mailable has the correct Order instance
            $hasTo = $mail->hasTo($order->user->email);
            $hasOrder = isset($mail->order) && $mail->order->id === $order->id;

            // Rendered view should contain the order number
            $rendered = (string) $mail->render();
            $containsOrderNumber = strpos($rendered, $order->order_number) !== false;

            return $hasTo && $hasOrder && $containsOrderNumber;
        });
    }
}
