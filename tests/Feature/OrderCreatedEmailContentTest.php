<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\User;
use App\Models\Event;
use App\Models\OrderItem;
use App\Models\TicketType;
use Illuminate\Support\Str;

class OrderCreatedEmailContentTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function order_created_email_contains_correct_profile_links()
    {
        // 1. Setup data (in-memory)
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $event = new Event([
            'title' => 'Sample Event',
            'venue_name' => 'Sample Venue',
            'event_date' => now(),
        ]);

        $order = new Order([
            'uuid' => (string) Str::ulid(),
            'order_number' => 'ORD-12345',
            'total_amount' => 50000,
        ]);
        $order->id = 999;

        $ticketType = new TicketType(['name' => 'Early Bird']);
        $item = new OrderItem(['quantity' => 1, 'total_price' => 50000]);
        $item->setRelation('ticketType', $ticketType);

        $order->setRelation('user', $user);
        $order->setRelation('event', $event);
        $order->setRelation('orderItems', collect([$item]));

        // 2. Render the mailable
        $mailable = new OrderCreated($order);
        $html = $mailable->render();

        // 3. Assertions
        $expectedProfileUrl = route('profile.orders.show', $order->uuid);
        $expectedSearchUrl = route('events.index');

        $this->assertStringContainsString($expectedProfileUrl, $html, 'The "Upload Payment Proof" button should link to the profile order page.');
        $this->assertStringContainsString($expectedSearchUrl, $html, 'The footer link should link to the event search page.');
        $this->assertStringContainsString('Upload Payment Proof', $html);
        $this->assertStringContainsString('Search Event', $html);
        $this->assertStringContainsString($order->order_number, $html);
    }
}
