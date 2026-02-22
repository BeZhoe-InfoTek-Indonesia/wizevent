<?php

namespace App\Livewire\Order;

use App\Models\Order;
use App\Services\FileBucketService;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app-visitor')]
class OrderStatus extends Component
{
    use WithFileUploads;

    public $paymentProof = null;
    public string $statusMessage = '';
    public Order $order;

    public function mount(string $orderNumber)
    {
        $this->order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['event.seoMetadata', 'orderItems.ticketType', 'paymentProof.fileBucket', 'tickets'])
            ->firstOrFail();
    }

    public function rules(): array
    {
        return [
            'paymentProof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function uploadProof(FileBucketService $fileBucketService, OrderService $orderService)
    {
        $this->validate();

        if (! $this->paymentProof) {
            $this->statusMessage = __('payment_proof.no_file_selected');
            return;
        }

        $fileBucket = $fileBucketService->upload(
            $this->order,
            $this->paymentProof,
            'payment-proofs'
        );

        $orderService->uploadPaymentProof($this->order, $fileBucket->id);

        // Refresh order relationship
        $this->order = $this->order->fresh(['paymentProof.fileBucket']);
        $this->paymentProof = null;
        $this->statusMessage = __('payment_proof.uploaded_successfully');
    }

    #[Computed]
    public function statusColor(): string
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

    #[Computed]
    public function status_label(): string
    {
        return __("order.status.{$this->order->status}");
    }

    #[Computed]
    public function paymentStatusLabel(): string
    {
        if (! $this->order->paymentProof) {
            return __('payment_proof.not_uploaded');
        }

        return __("payment_proof.status.{$this->order->paymentProof->status}");
    }

    public function render(): View
    {
        $seo = $this->order->event->seoMetadata ?? null;

        return view('livewire.order.order-status', [
            'order' => $this->order,
            'pageTitle' => $seo->title ?? $this->order->event->title,
            'metaDescription' => $seo->description ?? null,
            'metaImage' => $seo->og_image ?? $this->order->event->banner?->url,
            'metaKeywords' => $seo->keywords ?? null,
            'canonicalUrl' => $seo->canonical_url ?? route('orders.status', $this->order->order_number),
        ]);
    }
}
