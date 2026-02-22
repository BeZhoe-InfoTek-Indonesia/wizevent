<?php

namespace App\Livewire\Order;

use App\Jobs\NotifyPaymentForReview;
use App\Models\Order;
use App\Services\FileBucketService;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app-visitor')]
class BookingConfirmation extends Component
{
    use WithFileUploads;
    public Order $order;

    #[Validate]
    public $paymentProof = null;

    public string $statusMessage = '';

    // Visitor Details
    public string $visitorName = '';
    public string $visitorEmail = '';
    public string $visitorPhone = '';
    public string $visitorIdentityCard = '';
    public string $visitorTitle = 'Mr.';

    public ?string $selectedPaymentMethod = null;
    public bool $showSeeAll = false;
    public bool $sameAsContact = true;

    public function updatedSameAsContact($value): void
    {
        if ($value) {
            $user = Auth::user();
            if ($user && (empty($user->identity_number) || empty($user->mobile_phone_number))) {
                $this->sameAsContact = false;
                $this->dispatch('show-notification', [
                    'message' => 'Your profile is incomplete. Please fill in your identity number and mobile phone number in your profile first.',
                    'type' => 'warning'
                ]);
                return;
            }
            $this->syncContactDetails();
        }
    }

    protected function syncContactDetails(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->visitorName = $user->name;
            $this->visitorEmail = $user->email;
            
            if ($user->mobile_phone_number) {
                // Remove any existing formatting and re-apply it
                $cleaned = preg_replace('/[^0-9]/', '', $user->mobile_phone_number);
                $cleaned = substr($cleaned, 0, 13);
                $this->visitorPhone = trim(chunk_split($cleaned, 4, '-'), '-');
            }
            
            if ($user->identity_number) {
                $this->visitorIdentityCard = $user->identity_number;
            }
        }
    }

    public function toggleSeeAll(): void
    {
        $this->showSeeAll = !$this->showSeeAll;
    }

    public function getPaymentMethodsProperty()
    {
        $methods = [
            ['id' => 'bca_va', 'name' => 'BCA Virtual Account', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/200px-Bank_Central_Asia.svg.png'],
            ['id' => 'mandiri_va', 'name' => 'Mandiri Virtual Account', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/200px-Bank_Mandiri_logo_2016.svg.png'],
            ['id' => 'bri_va', 'name' => 'BRI Virtual Account', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_Logo.svg/200px-BRI_Logo.svg.png'],
            ['id' => 'bni_va', 'name' => 'BNI Virtual Account', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Bank_Negara_Indonesia_logo.svg/200px-Bank_Negara_Indonesia_logo.svg.png'],
            ['id' => 'permata_va', 'name' => 'Permata Virtual Account', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Permata_Bank_logo.svg/200px-Permata_Bank_logo.svg.png'],
        ];

        if (!$this->showSeeAll) {
            return array_slice($methods, 0, 3);
        }

        return $methods;
    }

    public function updatedVisitorPhone($value): void
    {
        // Remove all non-digit characters
        $cleaned = preg_replace('/[^0-9]/', '', $value);

        // Limit to 13 digits (Indonesian mobile numbers: 8 followed by up to 12 digits)
        $cleaned = substr($cleaned, 0, 13);
        
        // Format with dashes every 4 digits
        $this->visitorPhone = trim(chunk_split($cleaned, 4, '-'), '-');
    }

    public function mount(string $orderNumber)
    {
        $this->order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['event.seoMetadata', 'orderItems.ticketType'])
            ->firstOrFail();

        if ($this->order->status !== 'pending_payment') {
            return redirect()->route('orders.status', $this->order->order_number);
        }

        // Prefill visitor details from user
        $user = Auth::user();
        if ($user) {
            // Only auto-enable sameAsContact if profile is complete
            if (!empty($user->identity_number) && !empty($user->mobile_phone_number)) {
                $this->syncContactDetails();
                $this->sameAsContact = true;
            } else {
                $this->sameAsContact = false;
                // Still fill name and email if possible
                $this->visitorName = $user->name;
                $this->visitorEmail = $user->email;
            }
        }
    }

    public function rules(): array
    {
        return [
            'paymentProof' => [
                'nullable', // Changed from required to nullable
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'visitorName' => 'required|string|max:255',
            'visitorEmail' => 'required|email|max:255',
            'visitorPhone' => [
                'required',
                'string',
                'regex:/^8\d{3}-\d{4}-\d{1,4}(?:-\d{1,4})?$/',
            ],
            'visitorIdentityCard' => 'required|string|digits:16',
        ];
    }

    public function getValidationAttributes(): array
    {
        return [
            'paymentProof' => __('payment_proof.label'),
        ];
    }

    public function confirmBooking(FileBucketService $fileBucketService, OrderService $orderService)
    {
        // Simulate extensive order preparation
        sleep(5);

        $this->validate();

        $fileBucketId = null;

        if ($this->paymentProof) {
            $fileBucket = $fileBucketService->upload(
                $this->order,
                $this->paymentProof,
                'payment-proofs'
            );
            $fileBucketId = $fileBucket->id;
        }

        $notes = "Visitor Details:\n";
        $notes .= "Name: {$this->visitorTitle} {$this->visitorName}\n";
        $notes .= "Email: {$this->visitorEmail}\n";
        $notes .= "Phone: {$this->visitorPhone}\n";
        $notes .= "ID Card: {$this->visitorIdentityCard}";

        // We use uploadPaymentProof method to save visitor details even if file is null
        // The service should handle nullable file_bucket_id if configured, 
        // or we might need to update the service.
        $paymentProof = $orderService->uploadPaymentProof($this->order, $fileBucketId, $notes);

        // Keep order in pending_verification. Dispatch a queued job to notify finance/admins for review.
        try {
            NotifyPaymentForReview::dispatch($this->order->refresh());
        } catch (\Throwable $e) {
            logger()->error('Failed to dispatch NotifyPaymentForReview job', ['order_id' => $this->order->id, 'error' => $e->getMessage()]);
        }

        $this->paymentProof = null;
        $this->statusMessage = __('payment_proof.uploaded_successfully');

        // Show success notification and redirect to event list
        $this->dispatch('show-success-notification', [
            'message' => 'Order confirmed successfully. Please check your email for payment instructions.'
        ]);

        return redirect()->route('events.index');
    }

    public function render(): View
    {
        $seo = $this->order->event->seoMetadata ?? null;

        return view('livewire.order.booking-confirmation', [
            'order' => $this->order,
            'pageTitle' => $seo->title ?? $this->order->event->title,
            'metaDescription' => $seo->description ?? null,
            'metaImage' => $seo->og_image ?? $this->order->event->banner?->url,
            'metaKeywords' => $seo->keywords ?? null,
            'canonicalUrl' => $seo->canonical_url ?? route('orders.confirmation', $this->order->order_number),
        ]);
    }
}
