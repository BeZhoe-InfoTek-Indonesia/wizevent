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
    public bool $isProcessing = false;

    /**
     * Process a scanned QR code content.
     * 
     * @param string $qrContent
     * @return void
     */
    public function scan(string $qrContent): void
    {
        if ($this->isProcessing) {
            return;
        }

        $this->isProcessing = true;
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

    /**
     * Handle manual ticket number check.
     * 
     * @return void
     */
    public function checkManual(): void
    {
        $this->resetFeedback();
        
        if (empty($this->manualTicketNumber)) {
            $this->status = 'error';
            $this->message = __('scanner.enter_ticket_number_error');
            return;
        }

        /** @var Ticket|null $ticket */
        $ticket = Ticket::where('ticket_number', $this->manualTicketNumber)->first();

        if (!$ticket) {
            $this->status = 'error';
            $this->message = __('scanner.ticket_not_found');
            return;
        }

        try {
            $this->processCheckIn($ticket);
        } catch (\Exception $e) {
            $this->status = 'error';
            $this->message = $e->getMessage();
        }
    }

    /**
     * Execute the check-in for a valid ticket.
     * 
     * @param Ticket $ticket
     * @return void
     */
    protected function processCheckIn(Ticket $ticket): void
    {
        $ticketService = new TicketService();
        $ticketService->markTicketAsUsed($ticket);
        
        $this->scannedTicket = $ticket->load(['orderItem.order.event', 'orderItem.order.user', 'ticketType']);
        $this->status = 'success';
        $this->message = __('scanner.check_in_successful');
    }

    /**
     * Reset the feedback state.
     * 
     * @return void
     */
    public function resetFeedback(): void
    {
        $this->status = null;
        $this->message = null;
        $this->scannedTicket = null;
        $this->isProcessing = false;
    }

    /**
     * Render the component view.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.ticket-scanner');
    }
}
