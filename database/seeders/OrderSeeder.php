<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Order Seeder
 *
 * Creates sample orders with order items and tickets for testing.
 */
class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visitors = User::role('Visitor')->get();

        if ($visitors->isEmpty()) {
            $this->command->error('No visitor users found. Please run UserSeeder first.');
            return;
        }

        $events = Event::published()->with('ticketTypes')->get();

        if ($events->isEmpty()) {
            $this->command->error('No published events found. Please run EventSeeder first.');
            return;
        }

        $orderStatuses = ['completed', 'pending_verification', 'pending_payment'];

        foreach ($visitors as $visitor) {
            // Create 2-3 orders per visitor
            $numOrders = rand(2, 3);

            for ($i = 0; $i < $numOrders; $i++) {
                $event = $events->random();
                $ticketTypes = $event->ticketTypes->where('is_active', true);

                if ($ticketTypes->isEmpty()) {
                    continue;
                }

                // Select 1-2 ticket types for this order
                $selectedTicketTypes = $ticketTypes->random(rand(1, 2));
                $status = $orderStatuses[array_rand($orderStatuses)];

                DB::beginTransaction();

                try {
                    // Calculate order totals
                    $subtotal = 0;
                    $orderItemsData = [];

                    foreach ($selectedTicketTypes as $ticketType) {
                        $quantity = rand(1, min(3, $ticketType->max_purchase));
                        $unitPrice = $ticketType->price;
                        $totalPrice = $unitPrice * $quantity;

                        $subtotal += $totalPrice;

                        $orderItemsData[] = [
                            'ticket_type_id' => $ticketType->id,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'total_price' => $totalPrice,
                        ];

                        // Update ticket type sold count if order is completed
                        if ($status === 'completed') {
                            $ticketType->increment('sold_count', $quantity);
                        }
                    }

                    $discountAmount = 0;
                    $taxAmount = $subtotal * 0.11; // 11% tax
                    $totalAmount = $subtotal + $taxAmount - $discountAmount;

                    // Create order
                    $order = Order::create([
                        'order_number' => 'ORD-'.now()->format('Ymd').'-'.strtoupper(substr(uniqid(), -6)),
                        'user_id' => $visitor->id,
                        'event_id' => $event->id,
                        'status' => $status,
                        'subtotal' => $subtotal,
                        'discount_amount' => $discountAmount,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                        'notes' => null,
                        'expires_at' => $status === 'pending_payment' ? now()->addHours(24) : null,
                        'completed_at' => $status === 'completed' ? now() : null,
                    ]);

                    $this->command->info("✓ Order created: {$order->order_number} for {$visitor->email}");

                    // Create order items and tickets
                    foreach ($orderItemsData as $orderItemData) {
                        $orderItem = OrderItem::create([
                            'order_id' => $order->id,
                            'ticket_type_id' => $orderItemData['ticket_type_id'],
                            'quantity' => $orderItemData['quantity'],
                            'unit_price' => $orderItemData['unit_price'],
                            'total_price' => $orderItemData['total_price'],
                        ]);

                        // Create tickets for completed orders
                        if ($status === 'completed') {
                            $ticketType = TicketType::find($orderItemData['ticket_type_id']);

                            for ($j = 0; $j < $orderItemData['quantity']; $j++) {
                                $ticketNumber = 'TKT-'.$order->id.'-'.strtoupper(substr(uniqid(), -8));

                                $ticket = Ticket::create([
                                    'order_item_id' => $orderItem->id,
                                    'ticket_type_id' => $ticketType->id,
                                    'ticket_number' => $ticketNumber,
                                    'holder_name' => $visitor->name,
                                    'status' => 'active',
                                    'qr_code_content' => null,
                                ]);

                                // Update QR code content with ticket ID
                                $ticket->update([
                                    'qr_code_content' => encrypt(json_encode([
                                        'ticket_id' => $ticket->id,
                                        'order_id' => $order->id,
                                        'event_id' => $event->id,
                                        'ticket_number' => $ticketNumber,
                                    ])),
                                ]);
                            }

                            $this->command->info("  ✓ Created {$orderItemData['quantity']} ticket(s) for order item {$orderItem->id}");
                        }
                    }

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->command->error("Failed to create order: {$e->getMessage()}");
                }
            }
        }

        $this->command->info('Order seeding completed successfully!');
    }
}
