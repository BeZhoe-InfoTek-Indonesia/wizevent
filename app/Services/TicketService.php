<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Ticket;

class TicketService
{
    /**
     * Generate tickets for a given order item.
     * 
     * @param OrderItem $orderItem
     * @return void
     */
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

    /**
     * Generate and save a QR code for a ticket.
     * 
     * @param Ticket $ticket
     * @return string
     */
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

    /**
     * Validate an encrypted QR code payload.
     * 
     * @param string $encryptedPayload
     * @throws \Exception
     * @return Ticket
     */
    public function validateQrCode(string $encryptedPayload): Ticket
    {
        try {
            $decrypted = decrypt($encryptedPayload);
            $payload = json_decode($decrypted, true);

            if (! isset($payload['ticket_id'])) {
                throw new \Exception(__('scanner.ticket_not_found'));
            }

            $ticket = Ticket::with(['orderItem.order.event', 'ticketType'])->find($payload['ticket_id']);

            if (! $ticket) {
                throw new \Exception(__('scanner.ticket_not_found'));
            }

            if ($ticket->ticket_number !== $payload['ticket_number']) {
                throw new \Exception(__('scanner.ticket_not_found'));
            }

            if ($ticket->status === 'used') {
                $time = $ticket->checked_in_at ? $ticket->checked_in_at->setTimezone(config('app.timezone'))->format('H:i') : '--:--';
                throw new \Exception(__('scanner.ticket_already_used', ['time' => $time]));
            }

            if ($ticket->status === 'cancelled' || $ticket->status === 'cancelled') {
                throw new \Exception(__('scanner.ticket_cancelled_error'));
            }

            if (! $ticket->canBeUsed()) {
                throw new \Exception(__('scanner.ticket_invalid_error'));
            }

            return $ticket;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new \Exception(__('scanner.ticket_invalid_error'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Mark a ticket as used/checked-in.
     * 
     * @param Ticket $ticket
     * @throws \Illuminate\Validation\ValidationException
     * @return Ticket
     */
    public function markTicketAsUsed(Ticket $ticket): Ticket
    {
        if ($ticket->status === 'used') {
            $time = $ticket->checked_in_at ? $ticket->checked_in_at->setTimezone(config('app.timezone'))->format('H:i') : '--:--';
            throw \Illuminate\Validation\ValidationException::withMessages(['ticket' => __('scanner.ticket_already_used', ['time' => $time])]);
        }

        if (! $ticket->canBeUsed()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['ticket' => __('scanner.ticket_invalid_error')]);
        }

        $ticket->markAsUsed();

        activity()
            ->performedOn($ticket)
            ->causedBy(auth()->user())
            ->log('Ticket checked in');

        return $ticket->refresh();
    }

    /**
     * Cancel a ticket.
     * 
     * @param Ticket $ticket
     * @return Ticket
     */
    public function cancelTicket(Ticket $ticket): Ticket
    {
        $ticket->cancel();

        activity()
            ->performedOn($ticket)
            ->causedBy(auth()->user())
            ->log('Ticket cancelled');

        return $ticket->refresh();
    }

    /**
     * Generate a PDF file for the ticket.
     * 
     * @param Ticket $ticket
     * @return string
     */
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
