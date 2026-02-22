<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentProof;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\OrderCreated;
use App\Mail\PaymentVerificationApproved;
use App\Mail\PaymentVerificationRejected;
use App\Jobs\ProcessOrderReservation;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;

class OrderService
{
    public function createOrder(array $data): Order
    {
        // Validate requested ticket quantities against current stock before creating the order.
        // This gives immediate feedback to the user if requested quantity is unavailable.
        $validationErrors = [];
        foreach ($data['items'] as $index => $item) {
            $ticketType = TicketType::find($item['ticket_type_id']);
            if (! $ticketType) {
                $validationErrors["items.{$index}"] = 'Ticket type not found';
                continue;
            }

            $quantity = (int) ($item['quantity'] ?? 0);
            if ($quantity <= 0) {
                $validationErrors["items.{$index}"] = 'Quantity must be at least 1';
                continue;
            }

            if (! $ticketType->is_available_for_sale) {
                $validationErrors["items.{$index}"] = 'Ticket not available for sale';
                continue;
            }

            if (! $ticketType->canPurchase($quantity)) {
                $validationErrors["items.{$index}"] = 'Requested quantity not available';
                continue;
            }
        }

        if (! empty($validationErrors)) {
            throw ValidationException::withMessages($validationErrors);
        }

        // Create order and items inside a transaction, but do NOT reserve tickets here.
        // Reservations are handled by a queued job to serialize access and avoid race conditions.
        $order = DB::transaction(function () use ($data) {
            $order = Order::create([
                'order_number' => (new Order)->generateOrderNumber(),
                'user_id' => $data['user_id'] ?? auth()->id(),
                'event_id' => $data['event_id'],
                // mark as pending_payment; queued job will perform reservation
                'status' => $data['status'] ?? 'pending_payment',
                'subtotal' => $data['subtotal'] ?? 0,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'total_amount' => $data['total_amount'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'expires_at' => $data['expires_at'] ?? now()->addHours(24),
            ]);

            foreach ($data['items'] as $item) {
                $ticketType = TicketType::find($item['ticket_type_id']);

                if (! $ticketType) {
                    throw ValidationException::withMessages([
                        "items.{$item['ticket_type_id']}" => 'Ticket type not found',
                    ]);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $ticketType->price,
                    'total_price' => $ticketType->price * $item['quantity'],
                ]);
            }

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Order created (awaiting reservation)');

            return $order->load('orderItems');
        });

        // Dispatch a job to perform the actual ticket reservation under DB locks.
        ProcessOrderReservation::dispatch($order->id);

        return $order;
    }

    public function updateOrderStatus(Order $order, string $status): Order
    {
        $validStatuses = ['pending_payment', 'pending_verification', 'completed', 'cancelled', 'expired'];

        if (! in_array($status, $validStatuses)) {
            throw ValidationException::withMessages(['status' => 'Invalid order status']);
        }

        $order->update(['status' => $status]);

        if ($status === 'completed') {
            $order->update(['completed_at' => now()]);
        }

        activity()
            ->performedOn($order)
            ->causedBy(auth()->user())
            ->withProperties(['new_status' => $status])
            ->log('Order status updated');

        return $order->refresh();
    }

    public function cancelOrder(Order $order, ?string $reason = null): Order
    {
        if (! $order->canBeCancelled()) {
            throw ValidationException::withMessages(['order' => 'Order cannot be cancelled']);
        }

        return DB::transaction(function () use ($order, $reason) {
            foreach ($order->orderItems as $item) {
                $ticketType = TicketType::lockForUpdate()->find($item->ticket_type_id);
                if ($ticketType) {
                    $ticketType->releaseTickets($item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'notes' => $reason ? ($order->notes ? $order->notes."\n".$reason : $reason) : $order->notes,
            ]);

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->withProperties(['reason' => $reason])
                ->log('Order cancelled');

            return $order->refresh();
        });
    }

    public function uploadPaymentProof(Order $order, ?int $fileBucketId, ?string $notes = null): ?PaymentProof
    {
        return DB::transaction(function () use ($order, $fileBucketId, $notes) {
            // Lock the order record to prevent race conditions
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();

            if (! in_array($lockedOrder->status, ['pending_payment', 'pending_verification'])) {
                throw ValidationException::withMessages(['order' => 'Order is not in pending payment or verification status']);
            }

            $paymentProof = $lockedOrder->paymentProof;

            if ($fileBucketId) {
                if ($paymentProof) {
                    // Update existing proof
                    $paymentProof->update([
                        'file_bucket_id' => $fileBucketId,
                        'status' => 'pending',
                        'rejection_reason' => null, // Clear any previous rejection reason
                        'verified_at' => null,
                        'verified_by' => null,
                    ]);
                } else {
                    // Create new proof
                    $paymentProof = PaymentProof::create([
                        'order_id' => $lockedOrder->id,
                        'file_bucket_id' => $fileBucketId,
                        'status' => 'pending',
                    ]);
                }
            }

            $updateData = ['status' => 'pending_verification'];
            if ($notes) {
                $updateData['notes'] = $notes;
            }

            $lockedOrder->update($updateData);

            $logMessage = $fileBucketId ? 'Payment proof uploaded' : 'Booking confirmed (WhatsApp verification)';

            activity()
                ->performedOn($lockedOrder)
                ->causedBy(auth()->user())
                ->log($logMessage);

            return $paymentProof ? $paymentProof->load('fileBucket') : null;
        });
    }

    public function markOrderAsExpired(Order $order): Order
    {
        if (! $order->is_expired) {
            throw ValidationException::withMessages(['order' => 'Order is not expired']);
        }

        return DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                $ticketType = TicketType::lockForUpdate()->find($item->ticket_type_id);
                if ($ticketType) {
                    $ticketType->releaseTickets($item->quantity);
                }
            }

            $order->update(['status' => 'expired']);

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Order expired');

            return $order->refresh();
        });
    }

    public function approvePayment(PaymentProof $paymentProof, \App\Models\User $verifier): Order
    {
        if (! $paymentProof->is_pending) {
             throw ValidationException::withMessages(['payment_proof' => 'Payment proof is not pending']);
        }

        return DB::transaction(function () use ($paymentProof, $verifier) {
            $paymentProof->approve($verifier->id);
            $order = $paymentProof->order;

            // Commit inventory
            foreach ($order->orderItems as $item) {
                 $ticketType = TicketType::lockForUpdate()->find($item->ticket_type_id);
                 if ($ticketType) {
                     $ticketType->commitTickets($item->quantity);
                 }
            }

            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Generate Tickets
            $ticketService = app(TicketService::class);
            foreach ($order->orderItems as $item) {
                $ticketService->generateTickets($item);
            }

            activity()
                ->performedOn($order)
                ->causedBy($verifier)
                ->log('Payment verified, order completed');

            \App\Jobs\ProcessOrderCompletion::dispatch($order);

            return $order->refresh();
        });
    }

    public function rejectPayment(PaymentProof $paymentProof, \App\Models\User $verifier, string $reason): Order
    {
        if (! $paymentProof->is_pending) {
             throw ValidationException::withMessages(['payment_proof' => 'Payment proof is not pending']);
        }

        return DB::transaction(function () use ($paymentProof, $verifier, $reason) {
            $paymentProof->reject($verifier->id, $reason);
            $paymentProof->order->update([
                'status' => 'pending_payment'
            ]);

            activity()
                ->performedOn($paymentProof->order)
                ->causedBy($verifier)
                ->withProperties(['reason' => $reason])
                ->log('Payment proof rejected');

            try {
                Mail::to($paymentProof->order->user)->queue(new PaymentVerificationRejected($paymentProof->order, $reason));
            } catch (\Exception $e) {
                logger()->error('Failed to send payment rejected email', ['error' => $e->getMessage()]);
            }

            return $paymentProof->order->refresh();
        });
    }

    public function approveManualOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                 $ticketType = TicketType::lockForUpdate()->find($item->ticket_type_id);
                 if ($ticketType) {
                     $ticketType->commitTickets($item->quantity);
                 }
            }

            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $ticketService = app(TicketService::class);
            foreach ($order->orderItems as $item) {
                $ticketService->generateTickets($item);
            }

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Order approved manually');

            \App\Jobs\ProcessOrderCompletion::dispatch($order);

            return $order->refresh();
        });
    }

    /**
     * Create multiple orders and dispatch a Bus batch to reserve tickets for them.
     * Returns the dispatched Batch instance.
     *
     * @param array $ordersData Array of order payloads (same shape as createOrder)
     */
    public function createOrdersBatch(array $ordersData): Batch
    {
        $createdOrders = [];

        DB::transaction(function () use ($ordersData, &$createdOrders) {
            foreach ($ordersData as $data) {
                $order = Order::create([
                    'order_number' => (new Order)->generateOrderNumber(),
                    'user_id' => $data['user_id'] ?? auth()->id(),
                    'event_id' => $data['event_id'],
                    'status' => $data['status'] ?? 'pending_payment',
                    'subtotal' => $data['subtotal'] ?? 0,
                    'discount_amount' => $data['discount_amount'] ?? 0,
                    'tax_amount' => $data['tax_amount'] ?? 0,
                    'total_amount' => $data['total_amount'] ?? 0,
                    'notes' => $data['notes'] ?? null,
                    'expires_at' => $data['expires_at'] ?? now()->addHours(24),
                ]);

                foreach ($data['items'] as $item) {
                    $ticketType = TicketType::find($item['ticket_type_id']);

                    if (! $ticketType) {
                        throw ValidationException::withMessages([
                            "items.{$item['ticket_type_id']}" => 'Ticket type not found',
                        ]);
                    }

                    $order->orderItems()->create([
                        'ticket_type_id' => $ticketType->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $ticketType->price,
                        'total_price' => $ticketType->price * $item['quantity'],
                    ]);
                }

                activity()
                    ->performedOn($order)
                    ->causedBy(auth()->user())
                    ->log('Order created (batch, awaiting reservation)');

                $createdOrders[] = $order;
            }
        });

        $jobs = array_map(fn($o) => new ProcessOrderReservation($o->id), $createdOrders);

        $batch = Bus::batch($jobs)
            ->name('order-reservations-'.now()->format('YmdHis'))
            ->then(function (Batch $batch) use ($createdOrders) {
                // All jobs completed successfully
                activity()
                    ->withProperties(['batch_id' => $batch->id, 'orders' => array_map(fn($o) => $o->id, $createdOrders)])
                    ->log('Batch order reservations completed');
            })
            ->catch(function (Batch $batch, \Throwable $e) use ($createdOrders) {
                // At least one job failed during processing
                activity()
                    ->withProperties(['batch_id' => $batch->id, 'error' => $e->getMessage()])
                    ->log('Batch order reservations encountered an error');

                // Mark orders as cancelled with a note about batch failure
                foreach ($createdOrders as $order) {
                    try {
                        $order->update([
                            'status' => 'cancelled',
                            'notes' => $order->notes ? $order->notes."\nBatch reservation failed" : 'Batch reservation failed',
                        ]);
                    } catch (\Throwable $ex) {
                        logger()->error('Failed to update order status after batch failure', ['order_id' => $order->id, 'error' => $ex->getMessage()]);
                    }
                }
            })
            ->finally(function (Batch $batch) use ($createdOrders) {
                // Final cleanup or metrics logging
                activity()
                    ->withProperties(['batch_id' => $batch->id, 'orders' => array_map(fn($o) => $o->id, $createdOrders)])
                    ->log('Batch order reservations finalized');
            })
            ->dispatch();

        return $batch;
    }
}
