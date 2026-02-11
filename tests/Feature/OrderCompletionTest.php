<?php

namespace Tests\Feature;

use App\Jobs\ProcessOrderCompletion;
use App\Mail\PaymentVerificationApproved;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_completion_generates_files_and_sends_email()
    {
        Mail::fake();
        Storage::fake('public');

        $user = User::factory()->create();
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending_payment'
        ]);

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1
        ]);

        $ticket = Ticket::factory()->create([
            'order_item_id' => $orderItem->id,
            'ticket_type_id' => $ticketType->id,
        ]);

        $job = new ProcessOrderCompletion($order);
        
        // Resolve dependencies
        $invoiceService = app(InvoiceService::class);
        $ticketService = app(TicketService::class);
        
        // Run the job logic directly
        $job->handle($invoiceService, $ticketService);

        // Assert Invoice PDF exists
        Storage::disk('public')->assertExists("orders/{$order->id}/invoice.pdf");

        // Assert Ticket PDF exists
        Storage::disk('public')->assertExists("orders/{$order->id}/tickets/{$ticket->id}.pdf");

        // Assert Email sent
        Mail::assertSent(PaymentVerificationApproved::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;
        });
    }
}
