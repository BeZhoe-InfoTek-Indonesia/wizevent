<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use App\Services\TicketService;
use Tests\TestCase;

class TicketQrSystemTest extends TestCase
{
    private TicketService $ticketService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ticketService = new TicketService();
    }

    public function test_can_generate_valid_qr_code()
    {
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $order = Order::factory()->create(['status' => 'completed']);
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1
        ]);

        $ticket = Ticket::create([
            'order_item_id' => $orderItem->id,
            'ticket_type_id' => $ticketType->id,
            'ticket_number' => 'TKT-123',
            'status' => 'active'
        ]);

        $qrContent = $this->ticketService->generateQrCode($ticket);

        $this->assertNotNull($qrContent);
        $this->assertEquals($qrContent, $ticket->fresh()->qr_code_content);

        $validatedTicket = $this->ticketService->validateQrCode($qrContent);
        $this->assertEquals($ticket->id, $validatedTicket->id);
    }

    public function test_cannot_validate_used_ticket()
    {
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $order = Order::factory()->create(['status' => 'completed']);
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id
        ]);

        $ticket = Ticket::create([
            'order_item_id' => $orderItem->id,
            'ticket_type_id' => $ticketType->id,
            'ticket_number' => 'TKT-123',
            'status' => 'active'
        ]);

        $qrContent = $this->ticketService->generateQrCode($ticket);

        $ticket->update(['status' => 'used']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ticket already used');

        $this->ticketService->validateQrCode($qrContent);
    }

    public function test_cannot_validate_tampered_qr_code()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid or tampered QR code');

        $this->ticketService->validateQrCode('tampered-content');
    }

    public function test_mark_ticket_as_used_logs_activity()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);
        $ticket = Ticket::factory()->create([
            'order_item_id' => $orderItem->id,
            'ticket_type_id' => $ticketType->id,
            'status' => 'active'
        ]);

        $this->ticketService->markTicketAsUsed($ticket);

        $this->assertEquals('used', $ticket->fresh()->status);
        $this->assertNotNull($ticket->fresh()->checked_in_at);

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $ticket->id,
            'description' => 'Ticket checked in'
        ]);
    }
}
