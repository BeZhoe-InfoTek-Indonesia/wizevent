<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function show(Ticket $ticket)
    {
        $ticket->load(['orderItem.order.event', 'orderItem.order.user', 'orderItem.ticketType']);
        // Ensure user owns the ticket or is admin
        if (auth()->id() !== $ticket->order->user_id && !auth()->user()->hasRole(['Super Admin', 'Event Manager'])) {
            abort(403);
        }

        // Generate QR Code if not present (though TicketService should handle it)
        // We can generate it on the fly for the view
        $qrCode = QrCode::size(200)->generate($ticket->qr_code_content);

        return view('tickets.show', compact('ticket', 'qrCode'));
    }
}
