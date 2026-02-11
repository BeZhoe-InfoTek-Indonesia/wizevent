<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.order.order-list', [
            'orders' => Order::where('user_id', Auth::id())
                ->with(['event'])
                ->latest()
                ->paginate(10),
        ]);
    }
}
