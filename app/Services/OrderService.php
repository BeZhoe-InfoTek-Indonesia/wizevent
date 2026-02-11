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

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
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
                $ticketType = TicketType::lockForUpdate()->find($item['ticket_type_id']);

                if (! $ticketType || ! $ticketType->canPurchase($item['quantity'])) {
                    throw ValidationException::withMessages([
                        "items.{$item['ticket_type_id']}" => 'Ticket type not available or insufficient quantity',
                    ]);
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $ticketType->price,
                    'total_price' => $ticketType->price * $item['quantity'],
                ]);

                $ticketType->reserveTickets($item['quantity']);
            }

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Order created');

            try {
                Mail::to($order->user)->queue(new OrderCreated($order));
            } catch (\Exception $e) {
                // Log error but don't fail transaction
                logger()->error('Failed to send order created email', ['error' => $e->getMessage()]);
            }

            return $order->load('orderItems');
        });
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

            if ($lockedOrder->status !== 'pending_payment') {
                throw ValidationException::withMessages(['order' => 'Order is not in pending payment status']);
            }

            $paymentProof = null;

            if ($fileBucketId) {
                $paymentProof = PaymentProof::create([
                    'order_id' => $lockedOrder->id,
                    'file_bucket_id' => $fileBucketId,    
                    'status' => 'pending',
                ]);
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
}
