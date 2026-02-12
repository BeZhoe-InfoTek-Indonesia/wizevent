<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Services\InvoiceService;
use App\Services\TicketService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function downloadInvoice(Order $order, InvoiceService $invoiceService): StreamedResponse
    {
        Gate::authorize('view', $order);

        $path = "orders/{$order->id}/invoice.pdf";

        if (! Storage::disk('public')->exists($path)) {
             $path = $invoiceService->generateInvoicePdf($order);
        }

        return Storage::disk('public')->download($path, "invoice-{$order->order_number}.pdf");
    }

    public function downloadTicket(Ticket $ticket, TicketService $ticketService): StreamedResponse
    {
        Gate::authorize('view', $ticket->orderItem->order);

        // $path = "orders/{$ticket->orderItem->order_id}/tickets/{$ticket->id}.pdf";
        // if (! Storage::disk('public')->exists($path)) {
             $path = $ticketService->generateTicketPdf($ticket);
        // }

        return Storage::disk('public')->download($path, "ticket-{$ticket->ticket_number}.pdf");
    }
}
