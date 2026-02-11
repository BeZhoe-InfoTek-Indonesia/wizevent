<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.visitor')]
class OrderStatus extends Component
{
    public Order $order;

    public function mount(string $orderNumber)
    {
        $this->order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['event', 'orderItems.ticketType', 'paymentProof.fileBucket', 'tickets'])
            ->firstOrFail();
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->order->status) {
            'pending_payment' => 'yellow',
            'pending_verification' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            'expired' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return __("order.status.{$this->order->status}");
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        if (! $this->order->paymentProof) {
            return __('payment_proof.not_uploaded');
        }

        return __("payment_proof.status.{$this->order->paymentProof->status}");
    }

    public function render(): View
    {
        return view('livewire.order.order-status', [
            'order' => $this->order,
        ]);
    }
}
