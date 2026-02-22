<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Services\CalendarService;
use App\Services\InvoiceService;
use App\Services\TicketService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{


    public function downloadInvoice(Order $order, InvoiceService $invoiceService): StreamedResponse
    {
        Gate::authorize('view', $order);

        if (! $order->canDownloadInvoice()) {
            abort(403, __('profile.invoice_not_available'));
        }

        $path = "orders/{$order->uuid}/invoice.pdf";

        if (! Storage::disk('public')->exists($path)) {
             $path = $invoiceService->generateInvoicePdf($order);
        }

        return Storage::disk('public')->download($path, "invoice-{$order->order_number}.pdf");
    }

    public function downloadTickets(Order $order, TicketService $ticketService): StreamedResponse
    {
        Gate::authorize('view', $order);

        $format = request()->query('format', 'png'); // Default to png for order tickets
        $driver = request()->query('driver', 'dompdf');

        if ($format === 'pdf') {
            $extension = $driver === 'browsershot' ? '-bs.pdf' : '.pdf';
            $path = "orders/{$order->uuid}/tickets/all{$extension}";
            
            if (! Storage::disk('public')->exists($path)) {
                $path = $ticketService->generateOrderTicketsPdf($order, $driver);
            }
            
            return Storage::disk('public')->download($path, "tickets-{$order->order_number}.pdf");
        }

        // Default PNG behavior
        $path = "orders/{$order->uuid}/tickets/all.png";

        if (! Storage::disk('public')->exists($path)) {
            $path = $ticketService->generateOrderTicketsImage($order);
        }

        return Storage::disk('public')->download($path, "tickets-{$order->order_number}.png");
    }

    public function downloadTicket(Ticket $ticket, TicketService $ticketService): StreamedResponse
    {
        Gate::authorize('view', $ticket->orderItem->order);

        $format = request()->query('format', 'pdf'); // Default to pdf for single ticket
        $driver = request()->query('driver', 'dompdf');

        if ($format === 'png') {
            $path = "orders/{$ticket->orderItem->order->uuid}/tickets/{$ticket->id}.png";
            if (! Storage::disk('public')->exists($path)) {
                $path = $ticketService->generateTicketPng($ticket);
            }
            return Storage::disk('public')->download($path, "ticket-{$ticket->ticket_number}.png");
        }

        $extension = $driver === 'browsershot' ? '-bs.pdf' : '.pdf';
        $path = "orders/{$ticket->orderItem->order->uuid}/tickets/{$ticket->id}{$extension}";
        
        if (! Storage::disk('public')->exists($path)) {
            $path = $ticketService->generateTicketPdf($ticket, $driver);
        }

        return Storage::disk('public')->download($path, "ticket-{$ticket->ticket_number}.pdf");
    }

    public function downloadCalendar(Order $order, CalendarService $calendarService): Response
    {
        Gate::authorize('view', $order);

        $event = $order->event;
        $icsContent = $calendarService->generateIcsContent($event);
        $filename = $calendarService->generateIcsFilename($event);

        return response($icsContent, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Length' => strlen($icsContent),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
