<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\PaymentVerificationApproved;
use App\Models\Order;
use App\Models\User;
use App\Models\Event;
use App\Models\OrderItem;
use App\Models\TicketType;
use Illuminate\Support\Str;
use Illuminate\Support\Number;

class PaymentApprovedEmailContentTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function payment_approved_email_contains_correct_content_and_links()
    {
        // 1. Setup data (in-memory to avoid DB side effects as per .cursorrules)
        $user = new User([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $event = new Event([
            'title' => 'Premium Jazz Night',
            'venue_name' => 'The Blue Note',
            'location' => 'Jakarta, Indonesia',
            'event_date' => now()->addDays(10),
        ]);

        $banner = new \App\Models\FileBucket([
            'file_path' => 'event-images/test.jpg',
            'url' => 'https://example.com/test.jpg',
        ]);
        $event->setRelation('banner', $banner);

        $order = new Order([
            'uuid' => (string) Str::ulid(),
            'order_number' => 'ORD-98765',
            'total_amount' => 1500000,
        ]);
        $order->id = 123;

        $ticketType = new TicketType(['name' => 'VIP Lounge']);
        $item = new OrderItem([
            'quantity' => 2, 
            'total_price' => 1500000,
            'ticket_type_id' => 1
        ]);
        $item->setRelation('ticketType', $ticketType);

        $order->setRelation('user', $user);
        $order->setRelation('event', $event);
        $order->setRelation('orderItems', collect([$item]));
        $order->setRelation('tickets', collect([])); // Required by mailable with() but iterated over items in view

        // 2. Render the mailable
        $mailable = new PaymentVerificationApproved($order);
        $html = $mailable->render();

        // 3. Assertions for content based on the new premium UI
        $this->assertStringContainsString(e(__('email.payment_successful')), $html);
        $this->assertStringContainsString(e(__('email.all_set_for_event')), $html);
        $this->assertStringContainsString(e($user->name), $html);
        $this->assertStringContainsString($order->order_number, $html);
        $this->assertStringContainsString(e($event->title), $html);
        
        // Check for the banner image
        $this->assertStringContainsString('img src', $html);
        $this->assertStringContainsString($event->banner->url, $html);
        $this->assertStringContainsString('alt="' . e($event->title) . '"', $html);

        // Check for the View Order Details button link
        $expectedStatusUrl = route('profile.orders.show', $order->order_number);
        $this->assertStringContainsString($expectedStatusUrl, $html);
        
        // Ensure Download Ticket PDF link is removed (as per user's latest change)
        $this->assertStringNotContainsString(__('email.download_ticket_pdf'), $html);

        // Check currency formatting (IDR)
        $formattedAmount = Number::currency($order->total_amount, 'IDR');
        $this->assertStringContainsString($formattedAmount, $html);

        // Check for footer branding
        $this->assertStringContainsString(e(config('app.name')), $html);
        $this->assertStringContainsString(__('email.footer_thank_you'), $html);
        $this->assertStringNotContainsString('123 Event Horizon Blvd', $html);
    }
}
