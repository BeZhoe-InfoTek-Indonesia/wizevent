<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TicketType;
use App\Models\Event;
use App\Services\OrderService;
use App\Jobs\ProcessOrderReservation;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

class OrderServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure database schema exists for tests (project avoids RefreshDatabase trait)
        $this->artisan('migrate');
    }
    public function test_create_order_dispatches_reservation_job(): void
    {
        Queue::fake();

        $user = User::withoutEvents(function () {
            return User::factory()->create();
        });

        $event = Event::factory()->create([
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $ticketType = TicketType::factory()->create([
            'quantity' => 100,
            'sold_count' => 0,
            'held_count' => 0,
            'min_purchase' => 1,
            'max_purchase' => 10,
            'is_active' => true,
            'sales_start_at' => now()->subHour(),
            'sales_end_at' => now()->addDay(),
            'event_id' => $event->id,
        ]);

        $service = app(OrderService::class);

        $data = [
            'user_id' => $user->id,
            'event_id' => $ticketType->event_id,
            'items' => [
                [
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => 2,
                ],
            ],
        ];

        $order = $service->createOrder($data);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending_payment',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => 2,
        ]);

        Queue::assertPushed(ProcessOrderReservation::class, function ($job) use ($order) {
            return $job->orderId === $order->id;
        });
    }

    public function test_create_orders_batch_dispatches_batch_of_reservations(): void
    {
        Bus::fake();

        $user = User::withoutEvents(function () {
            return User::factory()->create();
        });

        $eventA = Event::factory()->create(['created_by' => $user->id, 'updated_by' => $user->id]);
        $eventB = Event::factory()->create(['created_by' => $user->id, 'updated_by' => $user->id]);

        $ticketTypeA = TicketType::factory()->create(['is_active' => true, 'sales_start_at' => now()->subHour(), 'sales_end_at' => now()->addDay(), 'event_id' => $eventA->id]);
        $ticketTypeB = TicketType::factory()->create(['is_active' => true, 'sales_start_at' => now()->subHour(), 'sales_end_at' => now()->addDay(), 'event_id' => $eventB->id]);

        $service = app(OrderService::class);

        $ordersData = [
            [
                'user_id' => $user->id,
                'event_id' => $ticketTypeA->event_id,
                'items' => [ ['ticket_type_id' => $ticketTypeA->id, 'quantity' => 1] ],
            ],
            [
                'user_id' => $user->id,
                'event_id' => $ticketTypeB->event_id,
                'items' => [ ['ticket_type_id' => $ticketTypeB->id, 'quantity' => 2] ],
            ],
        ];

        $batch = $service->createOrdersBatch($ordersData);

        // When Bus is faked, a Batch instance is still returned. Ensure jobs were batched.
        Bus::assertBatched(function ($batch) {
            return $batch->jobs && count($batch->jobs) >= 1;
        });

        $this->assertNotNull($batch->id);
    }
}
