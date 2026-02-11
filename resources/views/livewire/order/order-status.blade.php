<div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8">
        {{-- Success Header --}}
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6 px-1">
                <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-gray-900 mb-2">Booking Successful!</h1>
            <p class="text-gray-500 text-sm md:text-base">Your reservation has been received and is awaiting confirmation.</p>
        </div>

        {{-- Order Card --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-6 md:p-8 space-y-6">
                {{-- Event Header --}}
                <div class="flex justify-between items-start gap-4">
                    <div class="space-y-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm font-bold bg-green-100 text-green-800 uppercase tracking-wide">
                            Processing
                        </span>
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 leading-tight">
                            {{ $order->event->title }}
                        </h2>
                    </div>
                </div>

                {{-- Order Details Grid --}}
                <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Order ID</p>
                        <p class="text-sm md:text-base font-black text-gray-900">#{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Price</p>
                        <p class="text-sm md:text-base font-black text-gray-900">IDR {{ number_format($order->total_amount) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Date</p>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm md:text-base font-bold text-gray-900">{{ $order->event->event_date?->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Time</p>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm md:text-base font-bold text-gray-900">{{ $order->event->event_date?->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-dashed border-gray-200"></div>

                {{-- WhatsApp Instruction --}}
                <div class="rounded-2xl bg-green-50 p-4 flex gap-3 md:gap-4 items-start border border-green-100">
                    <div class="bg-green-100 rounded-full p-1 flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-xs md:text-sm text-green-800 leading-relaxed font-medium">
                        To complete your booking, please send a screenshot of your payment receipt to our admin via <span class="font-bold">WhatsApp</span>. This helps us verify your transaction faster.
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="space-y-4">
                    <a href="https://wa.me/628123456789" target="_blank" class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white font-black py-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-green-500/20 active:scale-[0.98]">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span class="text-sm md:text-base">Chat with Admin (+62 812 3456 789)</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Footer Links --}}
        <div class="flex items-center justify-center gap-6 text-sm font-bold text-gray-500">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to My Bookings
            </a>
            <span class="text-gray-300">•</span>
        </div>
    </div>
</div>
