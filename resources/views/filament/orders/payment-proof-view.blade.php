<div x-data="{ modalOpen: true }" @keydown.window.escape="modalOpen = false" class="p-6">
    <div class="space-y-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ __('payment_proof.details') }}</h3>
        </div>
        
        @if($fileBucket->is_image)
            <div class="flex justify-center bg-gray-50 rounded-lg p-4">
                <img src="{{ $fileBucket->url }}" alt="{{ $paymentProof->file->original_filename ?? 'Payment Proof' }}" 
                     class="max-w-full max-h-96 rounded shadow-md object-contain">
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-blue-900">{{ $fileBucket->original_filename }}</p>
                        <p class="text-sm text-blue-600">{{ __('payment_proof.file_size') }}: {{ $fileBucket->formatted_size }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="space-y-2">
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">{{ __('payment_proof.uploaded_at') }}:</span>
                <span class="font-medium text-gray-900">{{ $paymentProof->created_at->format('d M Y, H:i') }}</span>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-gray-600">{{ __('payment_proof.status') }}:</span>
                <span @class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    'bg-yellow-100 text-yellow-800' => $paymentProof->status === 'pending',
                    'bg-green-100 text-green-800' => $paymentProof->status === 'approved',
                    'bg-red-100 text-red-800' => $paymentProof->status === 'rejected',
                ]">
                    {{ __("payment_proof.status.{$paymentProof->status}") }}
                </span>
            </div>
            
            @if($paymentProof->rejection_reason)
                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm font-medium text-red-800">
                        {{ __('payment_proof.rejection_reason') }}: {{ $paymentProof->rejection_reason }}
                    </p>
                </div>
            @endif
            
            @if($paymentProof->verified_at)
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('payment_proof.verified_at') }}:</span>
                    <span class="font-medium text-gray-900">{{ $paymentProof->verified_at->format('d M Y, H:i') }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
