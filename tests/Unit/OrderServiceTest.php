<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Order;
use App\Models\PaymentProof;
use App\Models\TicketType;
use App\Models\User;
use App\Services\OrderService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->orderService = app(OrderService::class);
    }

    public function test_create_order_reserves_tickets()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'quantity' => 10,
            'sold_count' => 0,
            'held_count' => 0,
            'is_active' => true,
            'min_purchase' => 1,
            'max_purchase' => 10,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeek(),
        ]);

        $data = [
            'event_id' => $event->id,
            'subtotal' => 100,
            'total_amount' => 100,
            'items' => [
                ['ticket_type_id' => $ticketType->id, 'quantity' => 2]
            ]
        ];

        $order = $this->orderService->createOrder($data);

        $this->assertEquals(2, $ticketType->refresh()->held_count);
        $this->assertEquals('pending_payment', $order->status);
    }

    public function test_upload_proof_updates_status()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending_payment']);
        // Create dummy FileBucket for id
        $fileBucket = \App\Models\FileBucket::factory()->create();
        
        $proof = $this->orderService->uploadPaymentProof($order, $fileBucket->id);
        
        $this->assertEquals('pending_verification', $order->refresh()->status);
        $this->assertEquals('pending', $proof->status);
    }

    public function test_approve_payment_commits_tickets()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['quantity' => 10, 'sold_count' => 0, 'held_count' => 2]);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending_verification']);
        // Attach item
        \App\Models\OrderItem::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => 2
        ]);
        
        $proof = PaymentProof::factory()->create(['order_id' => $order->id, 'status' => 'pending']);
        
        $admin = User::factory()->create(); 
        
        $this->orderService->approvePayment($proof, $admin);
        
        $this->assertEquals('completed', $order->refresh()->status);
        $this->assertEquals('approved', $proof->refresh()->status);
        
        $ticketType->refresh();
        $this->assertEquals(0, $ticketType->held_count);
        $this->assertEquals(2, $ticketType->sold_count);
    }
    
    public function test_reject_payment_reverts_status()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending_verification']);
        $proof = PaymentProof::factory()->create(['order_id' => $order->id, 'status' => 'pending']);
        $admin = User::factory()->create();
        
        $this->orderService->rejectPayment($proof, $admin, 'Invalid proof');
        
        $this->assertEquals('pending_payment', $order->refresh()->status);
        $this->assertEquals('rejected', $proof->refresh()->status);
    }
}
