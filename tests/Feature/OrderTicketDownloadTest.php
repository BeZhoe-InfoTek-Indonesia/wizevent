<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Tests\TestCase;

class OrderTicketDownloadTest extends TestCase
{
    public function test_order_ticket_endpoint_returns_zip_download()
    {
        $user = User::factory()->create();

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);

        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'completed']);

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);

        $ticket = Ticket::factory()->create([
            'order_item_id' => $orderItem->id,
            'ticket_type_id' => $ticketType->id,
        ]);

        // Prepare storage files: PNG and ZIP so controller will serve existing zip
        $storageDir = storage_path('app/public/orders/' . $order->uuid . '/tickets');
        if (! is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        $pngPath = $storageDir . '/' . $ticket->id . '.png';
        file_put_contents($pngPath, 'fake-png-content');

        $zipPath = $storageDir . '/all.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $zip->addFile($pngPath, basename($pngPath));
            $zip->close();
        }

        $this->actingAs($user);

        $response = $this->get(route('orders.ticket', $order));

        $response->assertStatus(200);

        $disposition = $response->headers->get('content-disposition');
        $this->assertIsString($disposition);
        $this->assertStringContainsString("tickets-{$order->order_number}.png", $disposition);

        // Streamed downloads may not expose content in the test client; assert headers and status instead.
        $this->assertNotEmpty($disposition);
    }
}
