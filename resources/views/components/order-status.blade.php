@php
    $status = $order->status ?? 'pending_payment';
    $progress = $status === 'pending_payment' ? 33 : ($status === 'pending_verification' ? 66 : ($status === 'completed' ? 100 : 0));
    $barClass = $status === 'completed' ? 'bg-green-600' : 'bg-red-600';
    $firstActive = $progress >= 33;
    $secondActive = $progress >= 66;
    $thirdActive = $progress >= 100;
@endphp

<div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-100">
    <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 mb-8 tracking-tight">Order Status</h3>
    <div class="relative">
        <div class="absolute inset-0 flex items-center mb-10" aria-hidden="true">
            <div class="w-full h-1 bg-gray-100 dark:bg-gray-700 rounded-full"></div>
            <div class="absolute left-0 h-1 {{ $barClass }} rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
        </div>

        <div class="relative z-10 flex items-center justify-between">
            <div class="flex-1 flex flex-col items-center text-center">
                <div class="w-12 h-12 {{ $firstActive ? ($status === 'completed' ? 'bg-green-600 text-white' : 'bg-red-600 text-white shadow-xl shadow-red-200') : 'bg-gray-100 text-gray-400' }} rounded-full flex items-center justify-center transition-all duration-500">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v6z"/></svg>
                </div>
                <div class="mt-4 text-sm font-black text-gray-900 dark:text-gray-100">Payment Made</div>
                <div class="text-[10px] uppercase font-bold text-gray-400 mt-1.5">{{ $order->created_at->format('M d, h:i A') }}</div>
            </div>

            <div class="flex-1 flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-full border-4 {{ $secondActive ? ($status === 'completed' ? 'border-green-600 bg-green-50' : 'border-red-600') : 'border-gray-100' }} flex items-center justify-center shadow-sm relative transition-all duration-500">
                    @if($secondActive)
                        <div class="w-10 h-10 {{ $status === 'completed' ? 'bg-green-600 text-white' : 'bg-red-600 text-white shadow-lg shadow-red-200' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2h12v6l-4 4 4 4v6H6v-6l4-4-4-4V2zm10 14.5l-4-4-4 4V18h8v-1.5zM12 9.5l4-4V4H8v1.5l4 4z"/></svg>
                        </div>
                    @else
                        <div class="w-10 h-10 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="mt-3 text-sm font-black text-gray-900 dark:text-gray-100">Verification</div>
                <div class="text-[10px] uppercase font-bold text-gray-400 mt-1.5">
                    {{ $status === 'pending_verification' ? 'In Progress' : ($status === 'completed' ? ($order->completed_at ? $order->completed_at->format('M d, h:i A') : $order->created_at->format('M d, h:i A')) : 'Pending') }}
                </div>
            </div>

            <div class="flex-1 flex flex-col items-center text-center">
                <div class="w-12 h-12 {{ $thirdActive ? 'bg-green-600 text-white shadow-xl shadow-green-200' : 'bg-gray-100 text-gray-400' }} rounded-full flex items-center justify-center transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="mt-4 text-sm font-black text-gray-900 dark:text-gray-100">Completed</div>
                <div class="text-[10px] uppercase font-bold text-gray-400 mt-1.5">{{ $status === 'completed' ? 'Finalized' : 'Upcoming' }}</div>
            </div>
        </div>
    </div>
</div>
