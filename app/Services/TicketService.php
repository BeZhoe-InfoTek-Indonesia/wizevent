<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Ticket;

class TicketService
{
    public function generateTickets(OrderItem $orderItem): void
    {
        for ($i = 0; $i < $orderItem->quantity; $i++) {
            $ticketNumber = (new Ticket)->generateTicketNumber($orderItem->order_id);

            $ticket = Ticket::create([
                'order_item_id' => $orderItem->id,
                'ticket_type_id' => $orderItem->ticket_type_id,
                'ticket_number' => $ticketNumber,
                'holder_name' => null,
                'status' => 'active',
                'qr_code_content' => null,
            ]);

            $this->generateQrCode($ticket);
        }

        activity()
            ->performedOn($orderItem)
            ->log("Generated {$orderItem->quantity} tickets");
    }

    public function generateQrCode(Ticket $ticket): string
    {
        $payload = json_encode([
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'event_id' => $ticket->ticketType->event_id,
        ]);

        $encrypted = encrypt($payload);

        $ticket->update(['qr_code_content' => $encrypted]);

        return $encrypted;
    }

    public function validateQrCode(string $encryptedPayload): Ticket
    {
        try {
            $decrypted = decrypt($encryptedPayload);
            $payload = json_decode($decrypted, true);

            if (! isset($payload['ticket_id'])) {
                throw new \Exception('Invalid QR code format');
            }

            $ticket = Ticket::with(['orderItem.order.event', 'ticketType'])->find($payload['ticket_id']);

            if (! $ticket) {
                throw new \Exception('Ticket not found');
            }

            if ($ticket->ticket_number !== $payload['ticket_number']) {
                throw new \Exception('Ticket number mismatch');
            }

            if ($ticket->status === 'used') {
                throw new \Exception('Ticket already used');
            }

            if ($ticket->status === 'cancelled') {
                throw new \Exception('Ticket has been cancelled');
            }

            if (! $ticket->canBeUsed()) {
                throw new \Exception('Ticket cannot be used');
            }

            return $ticket;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new \Exception('Invalid or tampered QR code');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function markTicketAsUsed(Ticket $ticket): Ticket
    {
        if (! $ticket->canBeUsed()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['ticket' => 'Ticket cannot be used']);
        }

        $ticket->markAsUsed();

        activity()
            ->performedOn($ticket)
            ->causedBy(auth()->user())
            ->log('Ticket checked in');

        return $ticket->refresh();
    }

    public function cancelTicket(Ticket $ticket): Ticket
    {
        $ticket->cancel();

        activity()
            ->performedOn($ticket)
            ->causedBy(auth()->user())
            ->log('Ticket cancelled');

        return $ticket->refresh();
    }

    public function generateTicketPdf(Ticket $ticket): string
    {
        $ticket->load(['ticketType.event', 'orderItem.order']);

        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->generate($ticket->qr_code_content));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.ticket', [
            'ticket' => $ticket,
            'qrCode' => $qrCode,
        ]);

        $path = "orders/{$ticket->orderItem->order_id}/tickets/{$ticket->id}.pdf";
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
