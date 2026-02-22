<?php

namespace App\Livewire\Profile;

use App\Models\Order;
use App\Models\SettingComponent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\OrderService;

class YourOrders extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    public $startDate = null;
    public $endDate = null;
    public $categoryFilter = null;

    protected $queryString = [
        'statusFilter' => ['except' => 'all', 'as' => 'status'],
        'search' => ['except' => ''],
        'startDate' => ['except' => null, 'as' => 'start'],
        'endDate' => ['except' => null, 'as' => 'end'],
        'categoryFilter' => ['except' => null, 'as' => 'category'],
    ];

    public function mount()
    {
        // Authorization check - users can view their own orders
        if (! auth()->check()) {
            abort(403, 'You must be logged in to view your orders.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'startDate', 'endDate', 'categoryFilter', 'statusFilter']);
        $this->resetPage();
        $this->dispatch('reset-filters');
    }

    public function getOrdersProperty()
    {
        $query = Order::query()
            ->where('user_id', Auth::id())
            ->with(['event.banner', 'event.categories', 'orderItems.ticketType', 'paymentProof'])
            ->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                // Match order number
                $q->where('order_number', 'like', "%{$search}%");

                // Or match event title
                $q->orWhereHas('event', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });

                // Or match ticket numbers associated with the order
                $q->orWhereHas('tickets', function ($q3) use ($search) {
                    $q3->where('ticket_number', 'like', "%{$search}%");
                });
            });
        }

        if ($this->startDate || $this->endDate) {
            $query->whereHas('event', function ($q) {
                if ($this->startDate && $this->endDate) {
                    if ($this->startDate === $this->endDate) {
                        $q->whereDate('event_date', $this->startDate);
                    } else {
                        $q->whereBetween('event_date', [
                            $this->startDate . ' 00:00:00',
                            $this->endDate . ' 23:59:59'
                        ]);
                    }
                } elseif ($this->startDate) {
                    $q->where('event_date', '>=', $this->startDate . ' 00:00:00');
                } elseif ($this->endDate) {
                    $q->where('event_date', '<=', $this->endDate . ' 23:59:59');
                }
            });
        }

        if ($this->categoryFilter) {
            $query->whereHas('event.categories', function ($q) {
                $q->where('setting_components.id', $this->categoryFilter);
            });
        }

        return $query->paginate(10);
    }

    public function getCategoriesProperty()
    {
        return SettingComponent::whereHas('setting', function ($query) {
            $query->where('key', 'event_categories');
        })->get();
    }

    public function getActiveTicketsCountProperty()
    {
        return Order::where('orders.user_id', Auth::id())
            ->where('orders.status', 'completed')
            ->whereHas('event', function ($query) {
                $query->where('event_date', '>=', now());
            })
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->sum('order_items.quantity');
    }

    public function getTotalOrdersCountProperty()
    {
        return Order::where('user_id', Auth::id())->count();
    }

    public function getNextEventProperty()
    {
        $nextOrder = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereHas('event', function ($query) {
                $query->where('event_date', '>=', now());
            })
            ->with('event')
            ->get()
            ->sortBy('event.event_date')
            ->first();

        return $nextOrder ? $nextOrder->event : null;
    }

    public function cancelOrder(int $orderId, OrderService $orderService)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $orderService->cancelOrder($order, 'Cancelled by user');
            $this->dispatch('order-cancelled', message: __('profile.order_cancelled_successfully'));
        } catch (\Exception $e) {
            $this->addError('cancel', __('profile.order_cannot_be_cancelled'));
        }
    }

    public function render()
    {
        return view('livewire.profile.your-orders');
    }
}
