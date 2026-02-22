<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Mail\PaymentVerificationApproved;
use Illuminate\Support\Facades\Mail;

class TestPaymentApprovedEmail extends Command
{
    protected $signature = 'mail:test-approved {order_number?} {email?} {--log : Use the log driver}';
    protected $description = 'Send a test payment approved email';

    public function handle()
    {
        $orderNumber = $this->argument('order_number');
        $email = $this->argument('email');

        if (!$orderNumber) {
            $order = Order::where('status', 'completed')->latest()->first();
            if (!$order) {
                $order = Order::latest()->first();
            }
        } else {
            $order = Order::where('order_number', $orderNumber)->first();
        }

        if (!$order) {
            $this->error("Order not found!");
            return 1;
        }

        $recipient = $email ?? $order->user->email;

        $this->info("Sending PaymentVerificationApproved email for {$order->order_number} to {$recipient}...");

        try {
            $mailer = $this->option('log') ? Mail::mailer('log') : Mail::to($recipient);
            
            if ($this->option('log')) {
                $mailer->to($recipient)->send(new PaymentVerificationApproved($order));
                $this->info("Success! Check storage/logs/laravel.log.");
            } else {
                $mailer->send(new PaymentVerificationApproved($order));
                $this->info("Success! Check your mail catcher (e.g. Mailpit at http://localhost:8025).");
            }
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
        }

        return 0;
    }
}
