<?php

namespace App\Livewire\Event;

use App\Models\Event;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app-visitor')]
class Checkout extends Component
{
    public Event $event;

    public array $quantities = [];
    public float $subtotal = 0;
    public float $taxAmount = 0;
    public float $platformFeeAmount = 0;
    public float $totalAmount = 0;

    public function mount(string $slug)
    {
        $this->event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->with(['ticketTypes', 'banner'])
            ->firstOrFail();

        foreach ($this->event->ticketTypes as $ticketType) {
            $this->quantities[$ticketType->id] = 0;
        }

        $this->calculateTotal();
    }

    public function incrementQuantity($ticketTypeId)
    {
        $ticketType = $this->event->ticketTypes->find($ticketTypeId);
        if (!$ticketType) return;

        $currentQuantity = $this->quantities[$ticketTypeId] ?? 0;
        if ($currentQuantity < $ticketType->max_purchase && $ticketType->canPurchase($currentQuantity + 1)) {
            $this->quantities[$ticketTypeId]++;
            $this->calculateTotal();
        }
    }

    public function decrementQuantity($ticketTypeId)
    {
        $ticketType = $this->event->ticketTypes->find($ticketTypeId);
        if (!$ticketType) return;

        $currentQuantity = $this->quantities[$ticketTypeId] ?? 0;
        if ($currentQuantity > 0) {
            $this->quantities[$ticketTypeId]--;
            $this->calculateTotal();
        }
    }

    public function updateQuantity($ticketTypeId, $quantity)
    {
        $ticketType = $this->event->ticketTypes->find($ticketTypeId);
        if (!$ticketType) return;

        $quantity = (int) $quantity;

        // basic validation
        if ($quantity < 0) $quantity = 0;
        if ($ticketType->max_purchase > 0 && $quantity > $ticketType->max_purchase) {
            $quantity = $ticketType->max_purchase;
        }

        if (!$ticketType->canPurchase($quantity) && $quantity > 0) {
             // If cannot purchase the custom amount, maybe reset or clamp?
             // For now, let's strict check available count
             if ($quantity > $ticketType->available_count) {
                 $quantity = $ticketType->available_count;
             }
        }

        $this->quantities[$ticketTypeId] = $quantity;
        $this->calculateTotal();
    }

    protected function calculateTotal(): void
    {
        $this->subtotal = 0;
        $this->taxAmount = 0;
        $this->platformFeeAmount = 0;

        foreach ($this->event->ticketTypes as $ticketType) {
            $quantity = $this->quantities[$ticketType->id] ?? 0;

            if ($quantity > 0) {
                $basePrice = (float) $ticketType->price;
                $itemSubtotal = $basePrice * $quantity;
                
                $this->subtotal += $itemSubtotal;
                
                // Assuming taxes and fees are calculated on top of base price
                // As per screenshot: 10% tax, 6% platform fee
                $this->taxAmount += $itemSubtotal * 0.10;
                $this->platformFeeAmount += $itemSubtotal * 0.06;
            }
        }

        $this->totalAmount = $this->subtotal + $this->taxAmount + $this->platformFeeAmount;
    }

    public function proceedToCheckout(OrderService $orderService)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('redirect', route('events.checkout', $this->event->slug));
        }

        $items = [];
        foreach ($this->event->ticketTypes as $ticketType) {
            $quantity = $this->quantities[$ticketType->id] ?? 0;
            if ($quantity > 0) {
                $items[] = [
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $quantity,
                ];
            }
        }

        if (empty($items)) {
            session()->flash('error', __('Select at least one ticket.'));
            return;
        }

        $order = $orderService->createOrder([
            'event_id' => $this->event->id,
            'subtotal' => $this->subtotal,
            'discount_amount' => 0,
            'tax_amount' => $this->taxAmount,
            'total_amount' => $this->totalAmount,
            'items' => $items,
        ]);

        return redirect()->route('orders.confirmation', $order->order_number);
    }

    public function render(): View
    {
        return view('livewire.event.checkout', [
            'event' => $this->event,
            'selectedTickets' => $this->event->ticketTypes->filter(fn($t) => ($this->quantities[$t->id] ?? 0) > 0),
        ]);
    }
}
