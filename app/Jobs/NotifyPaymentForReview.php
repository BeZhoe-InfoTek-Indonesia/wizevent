<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\PaymentPendingReviewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyPaymentForReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        // Notify finance/admin role users via Notification system
        $admins = \App\Models\User::role('Finance Admin')->get();
        if ($admins->isEmpty()) {
            // fallback to users with permission 'finance.verify-payments' if no role found
            $admins = \App\Models\User::permission('finance.verify-payments')->get();
        }

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PaymentPendingReviewNotification($this->order));
        }
    }
}
