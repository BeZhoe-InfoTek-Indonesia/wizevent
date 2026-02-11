<?php

namespace App\Jobs;

use App\Mail\PaymentVerificationApproved;
use App\Models\Order;
use App\Services\InvoiceService;
use App\Services\TicketService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ProcessOrderCompletion implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function handle(InvoiceService $invoiceService, TicketService $ticketService): void
    {
        try {
            DB::transaction(function () use ($invoiceService, $ticketService) {
                // Generate Invoice
                $invoiceService->generateInvoicePdf($this->order);
                
                // Generate Tickets
                $this->order->load('tickets.orderItem');
                
                foreach ($this->order->tickets as $ticket) {
                    $ticketService->generateTicketPdf($ticket);
                }
            });

            // Send Email
            Mail::to($this->order->user)->send(new PaymentVerificationApproved($this->order));
            
        } catch (\Exception $e) {
            Log::error('Order completion processing failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);
            
            // Re-throw so the job is marked as failed and can be retried
            throw $e;
        }
    }
}
