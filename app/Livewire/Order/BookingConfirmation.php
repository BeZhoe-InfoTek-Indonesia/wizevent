<?php

namespace App\Livewire\Order;

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
            $this->syncContactDetails();
        }
    }

    protected function syncContactDetails(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->visitorName = $user->name;
            $this->visitorEmail = $user->email;
            // You might want to prefill phone if available in your User model
            // $this->visitorPhone = $user->phone; 
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
            ->with(['event', 'orderItems.ticketType'])
            ->firstOrFail();

        if ($this->order->status !== 'pending_payment') {
            return redirect()->route('orders.status', $this->order->order_number);
        }

        // Prefill visitor details from user or order notes if available (parsing notes is complex, easier to prefill from User)
        // For this iteration, we prefill from Auth User as a starting point if no notes exist
        $user = Auth::user();
        if ($user) {
            $this->syncContactDetails();
            $this->sameAsContact = true;
        }
    }

    public function rules(): array
    {
        return [
            'paymentProof' => [
                'nullable', // Changed from required to nullable
                'file',
                'mimes:jpg,jpeg,png,pdf',
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
        $orderService->uploadPaymentProof($this->order, $fileBucketId, $notes);

        $this->paymentProof = null;
        $this->statusMessage = __('payment_proof.uploaded_successfully');

        return redirect()->route('orders.status', $this->order->order_number);
    }

    public function render(): View
    {
        return view('livewire.order.booking-confirmation', [
            'order' => $this->order,
        ]);
    }
}
