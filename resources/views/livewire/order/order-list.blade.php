<div>
    <h3 class="text-2xl font-extrabold text-[#1E293B] tracking-tight mb-6">My Orders</h3>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-[24px] border border-[#E2E8F0] p-6 hover:shadow-md transition-shadow flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="space-y-3 flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                {{ match($order->status) {
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled', 'expired' => 'bg-red-100 text-red-700',
                                    'pending_verification' => 'bg-blue-100 text-blue-700',
                                    'pending_payment' => 'bg-yellow-100 text-yellow-700',
                                    default => 'bg-gray-100 text-gray-700'
                                } }}">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                            <span class="text-xs font-bold text-[#64748B]">#{{ $order->order_number }}</span>
                            <span class="text-xs font-bold text-[#64748B] px-2">•</span>
                            <span class="text-xs font-bold text-[#64748B]">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div>
                             <h4 class="font-black text-lg text-[#1E293B] leading-tight">{{ $order->event->title ?? 'Unknown Event' }}</h4>
                             <p class="text-sm font-bold text-[#1A8DFF] mt-1">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        @if($order->status === 'pending_payment')
                            <a href="{{ route('orders.confirmation', $order->order_number) }}" 
                               class="flex-1 md:flex-none text-center px-6 py-3 bg-[#1A8DFF] hover:bg-[#0077EE] text-white font-bold uppercase tracking-wide text-xs rounded-xl transition-all shadow-lg shadow-[#1A8DFF]/20">
                                Pay Now
                            </a>
                        @endif
                        
                        <a href="{{ route('orders.status', $order->order_number) }}" 
                           class="flex-1 md:flex-none text-center px-6 py-3 bg-white border border-[#E2E8F0] hover:bg-gray-50 text-[#64748B] font-bold uppercase tracking-wide text-xs rounded-xl transition-all">
                            Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-[24px] border border-[#E2E8F0]">
            <div class="w-16 h-16 bg-[#F1F5F9] rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-[#1E293B] mb-1">No orders yet</h3>
            <p class="text-[#64748B] text-sm mb-6">Looks like you haven't purchased any tickets.</p>
            <a href="{{ route('events.index') }}" class="inline-block px-8 py-3 bg-[#1E293B] text-white font-bold rounded-xl hover:bg-black transition-colors">
                Browse Events
            </a>
        </div>
    @endif
</div>
