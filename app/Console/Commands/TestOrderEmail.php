<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Mail;

class TestOrderEmail extends Command
{
    protected $signature = 'mail:test-order {order_number?} {email?}';
    protected $description = 'Send a test order confirmation email';

    public function handle()
    {
        $orderNumber = $this->argument('order_number');
        $email = $this->argument('email');

        if (!$orderNumber) {
            $order = Order::latest()->first();
        } else {
            $order = Order::where('order_number', $orderNumber)->first();
        }

        if (!$order) {
            $this->error("Order not found!");
            return 1;
        }

        $recipient = $email ?? $order->user->email;

        $this->info("Sending OrderCreated email for {$order->order_number} to {$recipient}...");

        try {
            Mail::to($recipient)->send(new OrderCreated($order));
            $this->info("Success! Check your mail catcher (e.g. Mailpit at http://localhost:8025 or MailHog).");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
        }

        return 0;
    }
}
