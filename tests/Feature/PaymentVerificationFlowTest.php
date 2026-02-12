<?php

namespace Tests\Feature;

use App\Mail\OrderCreated;
use App\Mail\PaymentVerificationApproved;
use App\Models\Event;
use App\Models\Order;
use App\Models\TicketType;
use App\Models\User;
use App\Services\FileBucketService;
use App\Services\OrderService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentVerificationFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_full_payment_verification_flow()
    {
        Mail::fake();
        Storage::fake('public');

        // 1. Setup Data
        $user = User::factory()->create();
        $admin = User::factory()->create(); 
        
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'quantity' => 10,
            'sold_count' => 0,
            'held_count' => 0,
            'is_active' => true,
            'min_purchase' => 1,
            'max_purchase' => 5, // Ensure validation passes
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeek(),
            'price' => 50000,
        ]);

        $orderService = app(OrderService::class);
        $fileBucketService = app(FileBucketService::class);

        // 2. User Creates Order
        $this->actingAs($user);
        $orderData = [
            'event_id' => $event->id,
            'subtotal' => 100000,
            'total_amount' => 100000,
            'items' => [
                ['ticket_type_id' => $ticketType->id, 'quantity' => 2]
            ]
        ];

        $order = $orderService->createOrder($orderData);

        // Verify Order Created
        $this->assertEquals('pending_payment', $order->status);
        $this->assertEquals(2, $ticketType->refresh()->held_count);
        $this->assertEquals(0, $ticketType->sold_count);

        // Verify OrderCreated Email Sent
        Mail::assertQueued(OrderCreated::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;
        });

        // 3. User Uploads Proof
        $file = UploadedFile::fake()->image('proof.jpg');
        // FileBucketService might rely on checking file type via request or explicit mime. 
        // Assuming upload method takes Model, UploadedFile, directory.
        $fileBucket = $fileBucketService->upload($order, $file, 'payment-proofs');
        
        $proof = $orderService->uploadPaymentProof($order, $fileBucket->id);

        // Verify Status Updated
        $this->assertEquals('pending_verification', $order->refresh()->status);
        $this->assertEquals('pending', $proof->status);

        // 4. Admin Approves Payment
        $this->actingAs($admin);
        $order = $orderService->approvePayment($proof, $admin);

        // Verify Order Completed
        $this->assertEquals('completed', $order->status);
        $this->assertNotNull($order->completed_at);
        $this->assertEquals('approved', $proof->refresh()->status);

        // Verify Inventory Committed
        $ticketType->refresh();
        $this->assertEquals(0, $ticketType->held_count);
        $this->assertEquals(2, $ticketType->sold_count);

        // Verify Tickets Generated
        $this->assertCount(2, $order->tickets);

        // Verify Approval Email Sent
        Mail::assertQueued(PaymentVerificationApproved::class);
    }
}
