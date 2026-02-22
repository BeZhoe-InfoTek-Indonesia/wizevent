<?php

namespace App\Livewire\Order;

use App\Models\Order;
use App\Services\FileBucketService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.app')]
class Show extends Component
{
    use WithFileUploads;

    public Order $order;
    public $paymentProof;

    public function mount(Order $order)
    {
        Gate::authorize('view', $order);
        
        $this->order = $order->load(['event', 'orderItems.ticketType', 'paymentProof', 'files' => function ($query) {
            $query->where('bucket_type', 'payment-proofs')->latest();
        }]);
    }

    public function uploadPaymentProof(FileBucketService $fileBucketService, OrderService $orderService)
    {
        $this->validate([
            'paymentProof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $fileBucket = $fileBucketService->upload(
                $this->order,
                $this->paymentProof,
                'payment-proofs'
            );

            $orderService->uploadPaymentProof($this->order, $fileBucket->id);

            $this->paymentProof = null;
            
            // Refresh order to show the uploaded file
            $this->order->refresh();

            Toaster::success('Payment proof uploaded successfully.');
        } catch (\Exception $e) {
            Toaster::error($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.order.show');
    }
}
