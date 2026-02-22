

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('profile.your_orders') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('profile.your_orders_desc') }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Active Tickets -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('profile.active_tickets') }}</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $this->activeTicketsCount }}</p>
            </div>
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-full">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('profile.total_orders') }}</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $this->totalOrdersCount }}</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>

        <!-- Next Event -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('profile.next_event') }}</p>
                <p class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1 truncate" title="{{ $this->nextEvent?->title }}">
                    {{ $this->nextEvent?->title ?? __('profile.no_upcoming_events_short') }}
                </p>
            </div>
            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-full shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div class="space-y-4">
        <!-- Search Row -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="max-w-xl mx-auto">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('profile.search_event') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        aria-label="{{ __('profile.search_placeholder') }}"
                        placeholder="{{ __('profile.search_placeholder') }}" 
                        class="block w-full pl-9 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 sm:text-sm transition-all"
                    >
                    <p class="mt-2 text-xs text-gray-400">{{ __('profile.search_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Other Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        
        <!-- Start Date -->
        <div class="md:col-span-4">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('profile.date_from') }}</label>
            <input
                type="date"
                wire:model.live="startDate"
                placeholder="{{ __('profile.date_from_placeholder') }}"
                class="block w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 sm:text-sm transition-all"
            >
        </div>

        <!-- End Date -->
        <div class="md:col-span-4">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('profile.date_to') }}</label>
            <input
                type="date"
                wire:model.live="endDate"
                placeholder="{{ __('profile.date_to_placeholder') }}"
                class="block w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 sm:text-sm transition-all"
            >
        </div>

        <div class="md:col-span-3">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('profile.category') }}</label>
            <select
                wire:model.live="categoryFilter"
                class="block w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 sm:text-sm transition-all"
            >
                <option value="">{{ __('profile.all_categories') }}</option>
                @foreach($this->categories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
        </div>

        <!-- Reset -->
        <div class="md:col-span-1">
            <button 
                wire:click="resetFilters"
                class="w-full h-[38px] flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg transition-colors group"
                title="Reset Filters"
            >
                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
        </div>
    </div>

    <!-- Orders List -->
    @if($this->orders->count() > 0)
        <div class="space-y-4">
             @foreach($this->orders as $order)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Left: Image -->
                        <div class="shrink-0">
                            <div class="w-full lg:w-24 lg:h-24 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 overflow-hidden">
                                @if($order->event->banner)
                                     <img src="{{ $order->event->banner->url }}" alt="{{ $order->event->title }}" class="w-full h-full object-cover">
                                @else
                                     <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                     </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Middle: Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <span class="text-xs font-mono text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $order->order_number }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border
                                    {{ $order->status === 'completed' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800' :
                                       ($order->status === 'pending_payment' ? 'bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800' :
                                       ($order->status === 'pending_verification' ? 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800' :
                                       ($order->status === 'cancelled' ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800' :
                                       'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700'))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 truncate" title="{{ $order->event->title }}">
                                {{ $order->event->title }}
                            </h3>

                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $order->event->event_date->format('M d, Y') }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $order->event->event_date->format('g:i A') }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                    {{ $order->total_tickets }} {{ $order->total_tickets > 1 ? __('profile.tickets') : __('profile.ticket') }}
                                </div>
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex flex-row lg:flex-col gap-2 justify-center shrink-0 w-full lg:w-auto mt-4 lg:mt-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-gray-100 dark:border-gray-700">
                             @if($order->status !== 'cancelled')
                                 <a href="{{ route('profile.orders.show', $order) }}" class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors gap-2 shadow-sm min-w-[140px]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ __('profile.view_ticket') }}
                                 </a>

                                 @if($order->canDownloadInvoice())
                                     <a href="{{ route('orders.invoice', $order) }}" class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-transparent border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors gap-2 min-w-[140px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        {{ __('profile.invoice') }}
                                     </a>
                                 @else
                                     <button disabled class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed min-w-[140px] gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        {{ __('profile.invoice') }}
                                     </button>
                                 @endif
                             @else
                                 <button disabled class="w-full lg:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed min-w-[140px] gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    {{ __('profile.unavailable') }}
                                 </button>
                             @endif
                             
                             @if($order->canBeCancelled())
                                 <button 
                                    wire:click="cancelOrder({{ $order->id }})"
                                    wire:confirm="{{ __('profile.cancel_order_confirm') }}"
                                    class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors gap-2 min-w-[140px]"
                                >
                                    {{ __('profile.cancel_order') }}
                                </button>
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
});
</script>
