<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\TicketType;
use App\Mail\OrderCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessOrderReservation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $orderId;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::with('orderItems')->find($this->orderId);

        if (! $order) {
            logger()->warning('ProcessOrderReservation: order not found', ['order_id' => $this->orderId]);
            return;
        }

        // Only process orders that are awaiting reservation (created with pending_payment)
        if ($order->status !== 'pending_payment') {
            logger()->info('ProcessOrderReservation: skipping order with status', ['order_id' => $order->id, 'status' => $order->status]);
            return;
        }

        $failed = false;
        $failureDetails = [];

        DB::transaction(function () use ($order, &$failed, &$failureDetails) {
            foreach ($order->orderItems as $item) {
                $ticketType = TicketType::lockForUpdate()->find($item->ticket_type_id);

                if (! $ticketType || ! $ticketType->canPurchase($item->quantity)) {
                    $failed = true;
                    $failureDetails[] = "ticket_type:{$item->ticket_type_id}";
                    break;
                }

                $ticketType->reserveTickets($item->quantity);
            }
        });

        if ($failed) {
            $note = 'Reservation failed for: '.implode(',', $failureDetails);
            $order->update([
                'status' => 'cancelled',
                'notes' => $order->notes ? $order->notes."\n".$note : $note,
            ]);

            activity()
                ->performedOn($order)
                ->causedBy(null)
                ->withProperties(['details' => $failureDetails])
                ->log('Order reservation failed and order cancelled');

            logger()->warning('Order reservation failed', ['order_id' => $order->id, 'details' => $failureDetails]);
            return;
        }

        // Success: mark order as pending_payment and send confirmation email
        $order->update(['status' => 'pending_payment']);

        activity()
            ->performedOn($order)
            ->causedBy(null)
            ->log('Tickets reserved, order awaiting payment');

        try {
            Mail::to($order->user)->queue(new OrderCreated($order));
        } catch (\Exception $e) {
            logger()->error('Failed to send order created email after reservation', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }
    }
}
