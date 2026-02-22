

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pl-2">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ __('profile.your_orders') }}</h2>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 font-medium mt-1">{{ __('profile.your_orders_desc') }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Active Tickets Card -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex items-center justify-between group border border-gray-100">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('profile.active_tickets') }}</p>
                <p class="text-3xl font-black text-gray-900 dark:text-gray-100 mt-1 tracking-tighter">{{ $this->activeTicketsCount }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:rotate-6 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex items-center justify-between group border border-gray-100">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('profile.total_orders') }}</p>
                <p class="text-3xl font-black text-gray-900 dark:text-gray-100 mt-1 tracking-tighter">{{ $this->totalOrdersCount }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:rotate-6 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>

        <!-- Next Event Card -->
        <div class="bg-white rounded-3xl shadow-lg p-6 flex items-center justify-between group border border-gray-100">
            <div class="min-w-0 pr-4">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('profile.next_event') }}</p>
                <p class="text-lg font-black text-gray-900 dark:text-gray-100 mt-1 truncate" title="{{ $this->nextEvent?->title }}">
                    {{ $this->nextEvent?->title ?? __('profile.no_upcoming_events_short') }}
                </p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:rotate-6 transition-transform shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Recent Orders Header & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 dark:border-gray-700 pb-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('profile.recent_orders') }}</h3>
        
        <div class="flex space-x-1 bg-gray-100 dark:bg-gray-700/50 p-1 rounded-lg overflow-x-auto">
            @php
                $filters = [
                    'all' => __('profile.status_all'),
                    'completed' => __('profile.status_completed'),
                    'pending_payment' => __('profile.status_pending_payment'),
                    'pending_verification' => __('profile.status_pending_verification'),
                    'cancelled' => __('profile.status_cancelled')
                ];
            @endphp

            @foreach($filters as $key => $label)
                <button 
                    wire:click="setStatusFilter('{{ $key }}')" 
                    class="px-4 py-1.5 text-sm font-medium rounded-md transition-all whitespace-nowrap {{ $statusFilter === $key ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>
    
    <!-- Filters & Search -->
    <div class="space-y-6">
        <!-- Search & Filter Card -->
        <div class="glass-panel rounded-3xl shadow-float p-6 md:p-8 bg-white/80 dark:bg-gray-800/80">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                <!-- Search -->
                <div class="lg:col-span-5 space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('profile.search_event') }}</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-red-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            placeholder="{{ __('profile.search_placeholder') }}" 
                            class="block w-full pl-12 pr-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition-all sm:text-sm"
                        >
                    </div>
                </div>

                <!-- Date Range -->
                <div class="lg:col-span-4 grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('profile.date_from') }}</label>
                        <input type="date" wire:model.live="startDate"
                            class="block w-full px-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition-all sm:text-sm cursor-pointer"
                        >
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('profile.date_to') }}</label>
                        <input type="date" wire:model.live="endDate"
                            class="block w-full px-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition-all sm:text-sm cursor-pointer"
                        >
                    </div>
                </div>

                <!-- Category -->
                <div class="lg:col-span-2 space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('profile.category') }}</label>
                    <select wire:model.live="categoryFilter"
                        class="block w-full px-4 py-4 rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition-all sm:text-sm cursor-pointer appearance-none"
                    >
                        <option value="">{{ __('profile.all_categories') }}</option>
                        @foreach($this->categories as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset -->
                <div class="lg:col-span-1">
                    <button wire:click="resetFilters" class="w-full h-[58px] flex items-center justify-center bg-white dark:bg-gray-900 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-2xl text-gray-400 hover:text-red-500 hover:border-red-200 dark:hover:border-red-900/30 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all active:scale-95 group">
                        <svg class="w-6 h-6 group-hover:rotate-180 transition-transform duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>
            <p class="mt-4 text-[10px] font-black uppercase tracking-[0.1em] text-gray-400/80 pl-1">
                {{ __('profile.search_desc') }}
            </p>
        </div>
    </div>

    <!-- Orders List -->
    @if($this->orders->count() > 0)
        <div class="space-y-4">
             @foreach($this->orders as $order)
                <div class="glass-panel rounded-3xl p-6 border border-white/50 dark:border-gray-700/50 bg-white/70 dark:bg-gray-800/70 shadow-float hover:shadow-lg transition-all duration-300 group overflow-hidden relative">
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-red-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    
                    <div class="flex flex-col lg:flex-row gap-8 relative z-10">
                        <!-- Left: Image -->
                        <div class="shrink-0 relative">
                            <div class="w-full lg:w-32 lg:h-32 rounded-2xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-400 overflow-hidden shadow-inner border border-gray-100 dark:border-gray-800">
                                @if($order->event->banner)
                                     <img src="{{ $order->event->banner->url }}" alt="{{ $order->event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                     <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                     </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Middle: Info -->
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">{{ $order->order_number }}</span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm
                                    {{ $order->status === 'completed' ? 'bg-green-50/50 text-green-600 border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800' :
                                       ($order->status === 'pending_payment' ? 'bg-yellow-50/50 text-yellow-600 border-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800' :
                                       ($order->status === 'pending_verification' ? 'bg-blue-50/50 text-blue-600 border-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800' :
                                       ($order->status === 'cancelled' ? 'bg-red-50/50 text-red-600 border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800' :
                                       'bg-gray-50/50 text-gray-600 border-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700'))) }}">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-3 truncate tracking-tighter" title="{{ $order->event->title }}">
                                {{ $order->event->title }}
                            </h3>

                            <div class="flex flex-wrap items-center gap-x-8 gap-y-3">
                                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <span class="text-red-500 drop-shadow-sm">📅</span>
                                    {{ $order->event->event_date->format('M d, Y') }}
                                </div>
                                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <span class="text-red-500 drop-shadow-sm">⏰</span>
                                    {{ $order->event->event_date->format('g:i A') }}
                                </div>
                                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <span class="text-red-500 drop-shadow-sm">🎟️</span>
                                    {{ $order->total_tickets }} {{ $order->total_tickets > 1 ? __('profile.tickets') : __('profile.ticket') }}
                                </div>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex flex-row lg:flex-col gap-3 justify-center items-center lg:items-end shrink-0 w-full lg:w-auto mt-6 lg:mt-0 pt-6 lg:pt-0 border-t lg:border-t-0 border-gray-100/50 dark:border-gray-700/50">
                             @if($order->status !== 'cancelled')
                                <a href="{{ route('profile.orders.show', $order) }}" class="w-full lg:w-48 py-3.5 rounded-2xl flex items-center justify-center text-[10px] font-black uppercase tracking-widest gap-2 shadow-lg active:scale-95 transition-all bg-red-600 text-white hover:bg-red-700 hover:-translate-y-1 hover:scale-105 duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ __('profile.view_ticket') }}
                                 </a>

                                 @if($order->canDownloadInvoice())
                                    <a href="{{ route('orders.invoice', $order) }}" class="glass-btn w-full lg:w-48 py-3.5 rounded-2xl flex items-center justify-center text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 gap-2 active:scale-95 transition-all border-2 border-red-500 hover:-translate-y-1 hover:scale-105 duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        {{ __('profile.invoice') }}
                                     </a>
                                 @endif
                             @else
                                 <div class="w-full lg:w-48 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center text-[10px] font-black uppercase tracking-widest text-gray-400 gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    {{ __('profile.cancelled') }}
                                 </div>
                             @endif
                        </div>
                    </div>
                </div>
             @endforeach
        </div>

        <div class="mt-6">
            {{ $this->orders->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('profile.no_orders_found') }}</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">{{ __('profile.no_orders_desc') }}</p>
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                {{ __('profile.browse_events') }}
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('order-cancelled', (event) => {
        alert(event.message);
    });
    Livewire.on('payment-uploaded', (event) => {
        alert(event.message || 'Payment proof uploaded successfully!');
    });
});
</script>
