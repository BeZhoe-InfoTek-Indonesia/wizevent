<?php

namespace App\Livewire\Profile;

use App\Models\Order;
use App\Services\FileBucketService;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.app-visitor')]
class ShowOrder extends Component
{
    use WithFileUploads;

    public Order $order;
    public $paymentProof;

    public function mount(Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $this->order = $order->load([
            'orderItems.ticketType',
            'tickets.ticketType',
            'paymentProof.fileBucket',
            'files' => function ($query) {
                $query->where('bucket_type', 'payment-proofs')->latest();
            },
        ]);
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
            
            $this->order->refresh();

            Toaster::success('Payment proof uploaded successfully.');
        } catch (\Exception $e) {
            Toaster::error($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.profile.show-order');
    }
}
