<?php

namespace App\Services;

use App\Models\PaymentProof;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function approvePayment(PaymentProof $paymentProof, int $verifierId): PaymentProof
    {
        if (! $paymentProof->canBeVerified()) {
            throw ValidationException::withMessages(['payment_proof' => 'Payment proof cannot be verified']);
        }

        return DB::transaction(function () use ($paymentProof, $verifierId) {
            $order = $paymentProof->order;

            $paymentProof->approve($verifierId);

            foreach ($order->orderItems as $orderItem) {
                $ticketType = TicketType::lockForUpdate()->find($orderItem->ticket_type_id);
                if ($ticketType) {
                    $ticketType->commitTickets($orderItem->quantity);
                }

                $this->ticketService->generateTickets($orderItem);
            }

            $order->update(['status' => 'completed', 'completed_at' => now()]);

            activity()
                ->performedOn($paymentProof)
                ->causedBy(auth()->user())
                ->log('Payment approved');

            return $paymentProof->load(['order', 'order.tickets', 'verifier']);
        });
    }

    public function rejectPayment(PaymentProof $paymentProof, int $verifierId, string $reason): PaymentProof
    {
        if (! $paymentProof->canBeVerified()) {
            throw ValidationException::withMessages(['payment_proof' => 'Payment proof cannot be verified']);
        }

        return DB::transaction(function () use ($paymentProof, $verifierId, $reason) {
            $order = $paymentProof->order;

            $paymentProof->reject($verifierId, $reason);

            foreach ($order->orderItems as $orderItem) {
                $ticketType = TicketType::lockForUpdate()->find($orderItem->ticket_type_id);
                if ($ticketType) {
                    $ticketType->releaseTickets($orderItem->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            activity()
                ->performedOn($paymentProof)
                ->causedBy(auth()->user())
                ->withProperties(['reason' => $reason])
                ->log('Payment rejected');

            return $paymentProof->load(['order', 'verifier']);
        });
    }

    public function getPendingVerificationProofs()
    {
        return PaymentProof::with(['order', 'order.user', 'order.event', 'fileBucket'])
            ->where('status', 'pending')
            ->whereHas('order', function ($query) {
                $query->where('status', 'pending_verification');
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
