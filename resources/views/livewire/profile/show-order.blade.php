@php $initialProfileTab = 'orders'; @endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Content -->
        <main class="flex-1">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('profile', ['tab' => 'orders']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>{{ __('Back to Orders') }}</span>
                </a>
            </div>
            <div class="space-y-6">
                @if($order->status === 'completed')
                    <div class="space-y-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                            <div class="flex items-center gap-5">
                                <div>
                                    <h1 class="text-4xl font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ __('Order Details') }}</h1>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">#{{ is_array($order->order_number ?? null) ? json_encode($order->order_number) : ($order->order_number ?? '') }} · {{ __('Placed on') }} {{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                            <div class="xl:col-span-2 space-y-8">
                                @include('components.order-status', ['order' => $order])

                                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-orange-100 dark:border-orange-500/20 shadow-sm relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-8 opacity-5">
                                        <svg class="w-32 h-32 text-orange-600 transform translate-x-10 -translate-y-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    </div>
                                    <div class="relative flex flex-row items-start gap-6">
                                        <div class="w-14 h-14 bg-orange-50 dark:bg-orange-500/10 rounded-2xl flex items-center justify-center text-orange-600 dark:text-orange-500 flex-shrink-0">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">{{ __('Payment Verified') }}</h4>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 leading-relaxed max-w-2xl">
                                                {{ __("You're all set! We've generated your secure tickets below. Please have the QR code ready to scan at the venue entrance.") }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6" x-data="{ expandedTicket: {{ $order->tickets->first()?->id ?? 'null' }} }">
                                    @forelse($order->tickets as $ticket)
                                        @php
                                            $ticketStatusResult = match($ticket->status) {
                                                'active' => ['color' => 'green', 'label' => 'Valid Ticket', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'used' => ['color' => 'gray', 'label' => 'Ticket Used', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'cancelled' => ['color' => 'red', 'label' => 'Cancelled', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                default => ['color' => 'gray', 'label' => 'Unknown', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            };
                                            $color = $ticketStatusResult['color'];
                                        @endphp

                                        <div class="group relative bg-white dark:bg-gray-800 rounded-3xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden"
                                            :class="{ 'ring-2 ring-orange-500/20 shadow-lg': expandedTicket === {{ $ticket->id }} }">
                                            
                                            <!-- Ticket Header (Always Visible) -->
                                            <div class="relative p-6 cursor-pointer select-none"
                                                @click="expandedTicket = (expandedTicket === {{ $ticket->id }} ? null : {{ $ticket->id }})">
                                                <div class="flex items-center justify-between gap-4">
                                                    <div class="flex items-center gap-4 sm:gap-6">
                                                        <!-- Icon Box -->
                                                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center transition-colors duration-300
                                                            {{ $ticket->status === 'active' ? 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-500' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                                            </svg>
                                                        </div>

                                                        <!-- Text Info -->
                                                        <div>
                                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                                <span class="font-mono text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">
                                                                    {{ $ticket->ticket_number }}
                                                                </span>
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border
                                                                    bg-{{ $color }}-50 text-{{ $color }}-700 border-{{ $color }}-100 dark:bg-{{ $color }}-500/10 dark:text-{{ $color }}-400 dark:border-{{ $color }}-500/20">
                                                                    {{ $ticket->status }}
                                                                </span>
                                                            </div>
                                                            <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white leading-tight">
                                                                {{ $ticket->holder_name ?? auth()->user()->name }}
                                                            </h3>
                                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                {{ is_array($ticket->ticketType->name ?? null) ? json_encode($ticket->ticketType->name) : ($ticket->ticketType->name ?? __('ticket_type.unknown')) }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Toggle Icon -->
                                                    <div class="flex-shrink-0 ml-auto">
                                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center transition-all duration-300"
                                                            :class="{ 'bg-orange-100 text-orange-600 rotate-180 dark:bg-orange-500/20 dark:text-orange-500': expandedTicket === {{ $ticket->id }} }">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Expandable Content -->
                                            <div x-show="expandedTicket === {{ $ticket->id }}"
                                                x-collapse
                                                class="border-t border-dashed border-gray-200 dark:border-gray-700">
                                                
                                                <div class="flex flex-col lg:flex-row">
                                                    <!-- QR Code Section -->
                                                    <div class="p-6 sm:p-8 flex flex-col items-center justify-center bg-gray-50/50 dark:bg-gray-800/50 lg:w-72 lg:border-r border-dashed border-gray-200 dark:border-gray-700 relative overflow-hidden">
                                                        <!-- Decorative circles imitating ticket notches -->
                                                        <div class="hidden lg:block absolute -right-3 top-0 bottom-0 w-6 flex flex-col justify-between py-2 pointer-events-none">
                                                            <div class="w-6 h-6 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 -mr-3"></div>
                                                            <div class="w-6 h-6 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 -mr-3"></div>
                                                        </div>

                                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 dark:bg-gray-900 mx-auto">
                                                            {!! QrCode::size(160)->generate($ticket->qr_code_content ?? route('orders.show', $order)) !!}
                                                        </div>
                                                        <p class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('Scan at Entrance') }}</p>
                                                        <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white font-mono tracking-wider">{{ $ticket->ticket_number }}</p>
                                                    </div>

                                                    <!-- Ticket Details Section -->
                                                    <div class="flex-1 p-6 sm:p-8 relative">
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                                                            <div>
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('event.label') }}</p>
                                                                <p class="text-sm sm:text-base font-bold text-gray-900 dark:text-white leading-tight">
                                                                    {{ is_array($order->event->title ?? null) ? json_encode($order->event->title) : ($order->event->title ?? '') }}
                                                                </p>
                                                            </div>

                                                            <div>
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Date & Time') }}</p>
                                                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                                    {{ $order->event->event_date->format('M d, Y') }} {{ __('at') }} {{ $order->event->event_date->format('H:i') }}
                                                                </p>
                                                            </div>

                                                            <div>
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Venue') }}</p>
                                                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                                    {{ is_array($order->event->location ?? null) ? json_encode($order->event->location) : ($order->event->location ?? '') }}
                                                                </p>
                                                            </div>

                                                            <div>
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Pass Type') }}</p>
                                                                <div class="inline-flex items-center px-2.5 py-1 rounded-md bg-orange-50 text-orange-700 dark:bg-orange-500/10 dark:text-orange-400 text-xs font-bold border border-orange-100 dark:border-orange-500/20">
                                                                    {{ is_array($ticket->ticketType->name ?? null) ? json_encode($ticket->ticketType->name) : ($ticket->ticketType->name ?? __('ticket_type.unknown')) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Action Buttons -->
                                                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                                            @if($ticket->status === 'active')
                                                                <a href="{{ route('tickets.download', ['ticket' => $ticket, 'driver' => 'browsershot']) }}" 
                                                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 hover:bg-black dark:bg-white dark:hover:bg-gray-100 text-white dark:text-gray-900 text-xs font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 shadow-md hover:shadow-lg">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                                    <span>{{ __('Download Ticket') }}</span>
                                                                </a>
                                                            @elseif($ticket->status === 'used')
                                                                <div class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs font-bold uppercase tracking-wider rounded-xl cursord-not-allowed opacity-75">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                    <span>{{ __('Checked In') }}</span>
                                                                </div>
                                                            @endif

                                                            <div class="hidden sm:block text-[10px] text-gray-400 ml-auto font-medium">
                                                                {{ __('order.singular_label') }} #{{ is_array($order->order_number ?? null) ? json_encode($order->order_number) : ($order->order_number ?? '') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border-2 border-dashed border-gray-200 dark:border-gray-700 text-center">
                                            <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                            </div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('No Tickets Found') }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ __('There do not seem to be any tickets associated with this order.') }}</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-4 mb-8">
                                        <div class="w-10 h-10 bg-orange-50 dark:bg-orange-500/10 rounded-xl flex items-center justify-center text-orange-600 dark:text-orange-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('Payment Summary') }}</h3>
                                    </div>

                                    <div class="space-y-4">
                                        @foreach($order->orderItems as $item)
                                            <div class="flex justify-between items-center p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/30">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                        {{ is_array($item->ticketType->name ?? null) ? json_encode($item->ticketType->name) : ($item->ticketType->name ?? __('ticket_type.unknown')) }}
                                                    </p>
                                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Quantity') }}: {{ $item->quantity }}x</p>
                                                </div>
                                                <p class="font-bold text-gray-900 dark:text-white font-mono">
                                                    Rp{{ number_format($item->total_price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        @endforeach
                                        
                                        <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-end gap-2">
                                            <div class="text-left w-full sm:w-auto">
                                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Total Paid') }}</p>
                                                <p class="text-[10px] text-gray-400">{{ __('All taxes included') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-8">
                                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                                    <h3 class="text-sm font-black text-gray-900 dark:text-white mb-6 uppercase tracking-wider">{{ __('Quick Actions') }}</h3>
                                    <div class="space-y-3">
                                        @if(Route::has('orders.ticket'))
                                            <a href="{{ route('orders.ticket', ['order' => $order, 'format' => 'pdf', 'driver' => 'browsershot']) }}" class="flex items-center justify-center gap-3 px-6 py-4 bg-gray-900 hover:bg-black dark:bg-white dark:hover:bg-gray-100 text-white dark:text-gray-900 font-bold rounded-xl text-xs uppercase tracking-wider transition-all active:scale-95 shadow-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                {{ __('Download All Tickets') }}
                                            </a>
                                        @endif
                                        @if(Route::has('orders.calendar'))
                                            <a href="{{ route('orders.calendar', $order) }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-white border-2 border-gray-100 text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ __('Add to Calendar') }}
                                            </a>
                                        @endif
                                        <a href="{{ route('orders.invoice', $order) }}" class="flex items-center justify-center gap-3 px-6 py-4 bg-white border-2 border-gray-100 text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            {{ __('Download Invoice') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-y-8">
                        <div>
                            <h1 class="text-4xl font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ __('Order Details') }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">#{{ is_array($order->order_number ?? null) ? json_encode($order->order_number) : ($order->order_number ?? '') }} · {{ __('Placed on') }} {{ $order->created_at->format('M d, Y') }}</p>
                        </div>

                        @include('components.order-status', ['order' => $order])

                        @if($order->status === 'pending_verification')
                            <div class="bg-gray-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-2xl">
                                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-600/20 rounded-full blur-3xl"></div>
                                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                                    <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                                        <svg class="w-10 h-10 text-red-500 animate-[spin_3s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                                    </div>
                                    <div class="flex-1 text-center md:text-left">
                                        <h3 class="text-2xl font-black tracking-tight">{{ __('Verifying Payment') }}</h3>
                                        <p class="text-gray-400 text-sm mt-2 leading-relaxed max-w-xl">{{ __("We've received your proof. Our team is now cross-checking the transaction. Hang tight, this usually takes just a few minutes!") }}</p>
                                    </div>
                                    <div class="px-6 py-3 bg-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest animate-pulse">
                                        {{ __('In Progress') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-10 shadow-sm border border-gray-100 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-8">
                                <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                            </div>
                            
                            <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-2 tracking-tight">{{ __('Payment Proof') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-10">{{ __('Please upload a screenshot of your transaction to speed up verification.') }}</p>

                            <div x-data="{ dragging: false }" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))" class="border-2 border-dashed rounded-3xl p-12 text-center transition-all relative group" :class="{ 'border-red-500 bg-red-50': dragging, 'border-gray-100 bg-gray-50/50 hover:bg-gray-50 hover:border-gray-200': !dragging }">
                                <input type="file" wire:model="paymentProof" x-ref="fileInput" class="hidden" accept=".jpg,.jpeg,.png">
                                <div wire:loading.remove wire:target="paymentProof">
                                    <div class="w-20 h-20 bg-white shadow-lg rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p class="text-gray-900 font-black tracking-tight mb-1 text-lg">{{ __('Upload Screenshot') }}</p>
                                    <p class="text-sm text-gray-500 mb-6">{{ __('Drag and drop or') }} <button type="button" @click="$refs.fileInput.click()" class="text-red-600 font-bold hover:underline">{{ __('browse files') }}</button></p>
                                    <div class="inline-flex items-center gap-4 px-4 py-2 bg-white rounded-full border border-gray-100 shadow-sm text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <span>{{ __('JPG, PNG') }}</span>
                                        <span class="w-1 h-1 bg-gray-200 rounded-full"></span>
                                        <span>{{ __('MAX 2MB') }}</span>
                                    </div>
                                </div>
                                <div wire:loading wire:target="paymentProof" class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm rounded-3xl z-20">
                                    <svg class="animate-spin h-12 w-12 text-red-600 mb-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                    <p class="text-lg font-black text-gray-900 tracking-tight">{{ __('Uploading...') }}</p>
                                </div>
                            </div>

                            @error('paymentProof')
                                <div class="mt-4 p-4 bg-red-50 text-red-700 text-sm font-bold rounded-xl border border-red-100 flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror

                            @isset($paymentProof)
                                <div class="mt-8 bg-gray-900 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center border border-white/10">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-white font-bold text-sm truncate max-w-[200px]">{{ method_exists($paymentProof, 'getClientOriginalName') ? $paymentProof->getClientOriginalName() : (is_string($paymentProof) ? $paymentProof : __('Payment Proof')) }}</p>
                                            <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest mt-0.5">{{ __('Ready to submit') }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="uploadPaymentProof" class="w-full sm:w-auto px-8 py-4 bg-white text-gray-900 text-[10px] font-black rounded-xl uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-lg active:scale-95">{{ __('Confirm Submission') }}</button>
                                </div>
                            @endisset

                            @if($order->files->where('bucket_type', 'payment-proofs')->isNotEmpty())
                                <div class="mt-10">
                                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">{{ __('Uploaded Documents') }}</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                                        @foreach($order->files->where('bucket_type', 'payment-proofs') as $file)
                                            <div class="relative group aspect-square rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 shadow-sm">
                                                <img src="{{ $file->url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                                    <a href="{{ $file->url }}" target="_blank" class="px-4 py-2 bg-white text-gray-900 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ __('Preview') }}</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-8 tracking-tight">{{ __('Order Information') }}</h3>
                            
                            <div class="flex flex-col sm:flex-row gap-6 mb-10">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gray-50 dark:bg-gray-700 overflow-hidden border border-gray-100 dark:border-gray-700 flex-shrink-0">
                                    @if($order->event->banner)
                                        <img src="{{ $order->event->banner->url }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">{{ is_array($order->event->title ?? null) ? json_encode($order->event->title) : ($order->event->title ?? '') }}</h4>
                                    <div class="flex flex-wrap items-center gap-2 mt-3">
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-gray-700">
                                            {{ optional($order->event->event_date) ? $order->event->event_date->format('M d, Y') : '' }}
                                        </span>
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-gray-700">
                                            {{ optional($order->event->event_date) ? $order->event->event_date->format('g:i A') : '' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ is_array($order->event->location ?? null) ? json_encode($order->event->location) : ($order->event->location ?? '') }}
                                    </p>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="font-medium text-gray-900 dark:text-gray-300">
                                            {{ is_array($item->ticketType->name ?? null) ? json_encode($item->ticketType->name) : ($item->ticketType->name ?? __('ticket_type.unknown')) }} 
                                            <span class="text-gray-400 ml-1">x{{ $item->quantity }}</span>
                                        </span>
                                        <span class="font-bold text-gray-900 dark:text-white">Rp{{ number_format($item->total_price, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                
                                <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-between items-end">
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">{{ __('Total Amount') }}</span>
                                        <span class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <a href="{{ route('orders.invoice', $order) }}" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-bold rounded-xl text-xs uppercase tracking-wider border border-gray-200 dark:border-gray-700 transition-all duration-300 group">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ __('Download Invoice') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
