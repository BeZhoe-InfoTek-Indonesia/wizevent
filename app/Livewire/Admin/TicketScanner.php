<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use App\Services\TicketService;
use Livewire\Component;

class TicketScanner extends Component
{
    public string $manualTicketNumber = '';
    public ?Ticket $scannedTicket = null;
    public ?string $status = null; // 'success', 'error'
    public ?string $message = null;

    public function scan(string $qrContent)
    {
        $this->resetFeedback();
        
        $ticketService = new TicketService();
        
        try {
            $ticket = $ticketService->validateQrCode($qrContent);
            $this->processCheckIn($ticket);
        } catch (\Exception $e) {
            $this->status = 'error';
            $this->message = $e->getMessage();
            
            activity()
                ->causedBy(auth()->user())
                ->withProperty('error', $e->getMessage())
                ->log('Failed ticket scan attempt');
        }
    }

    public function checkManual()
    {
        $this->resetFeedback();
        
        if (empty($this->manualTicketNumber)) {
            $this->status = 'error';
            $this->message = 'Please enter a ticket number';
            return;
        }

        $ticket = Ticket::where('ticket_number', $this->manualTicketNumber)->first();

        if (!$ticket) {
            $this->status = 'error';
            $this->message = 'Ticket not found';
            return;
        }

        try {
            $this->processCheckIn($ticket);
        } catch (\Exception $e) {
            $this->status = 'error';
            $this->message = $e->getMessage();
        }
    }

    protected function processCheckIn(Ticket $ticket)
    {
        $ticketService = new TicketService();
        $ticketService->markTicketAsUsed($ticket);
        
        $this->scannedTicket = $ticket->load(['orderItem.order.user', 'ticketType']);
        $this->status = 'success';
        $this->message = 'Check-in successful!';
    }

    public function resetFeedback()
    {
        $this->status = null;
        $this->message = null;
        $this->scannedTicket = null;
    }

    public function render()
    {
        return view('livewire.admin.ticket-scanner');
    }
}
