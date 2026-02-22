<div class="space-y-6">
    <!-- Header Card -->
    <div class="glass-panel rounded-3xl shadow-float p-6 md:p-8 bg-white/80 dark:bg-gray-800/80 mb-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ __('profile.wishlist') ?? 'Wishlist' }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 bg-red-500 rounded-full shadow-[0_0_8px_rgba(239,68,68,0.4)]"></span>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('profile.favorite_events') ?? 'Favorite Events' }}</p>
                </div>
            </div>
            
            @if($this->wishlist->count() > 0)
                <button 
                    wire:click="clearAll"
                    wire:confirm="Are you sure you want to clear your entire wishlist?"
                    class="glass-btn px-6 py-3 rounded-2xl flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition-all active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('profile.clear_all') ?? 'Clear All' }}
                </button>
            @endif
        </div>
    </div>

    @if($this->wishlist->count() > 0)
        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($this->wishlist as $favorite)
                @php
                    $event = $favorite->event;
                    $isSoldOut = $event->is_sold_out;
                @endphp
                <div class="group bg-white dark:bg-gray-800 rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:shadow-gray-200/50 dark:hover:shadow-none transition-all duration-500 flex flex-col h-full">
                    <!-- Image Area -->
                    <div class="relative h-60 overflow-hidden">
                        @if($event->banner)
                            <img 
                                src="{{ $event->banner->url }}" 
                                alt="{{ $event->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 {{ $isSoldOut ? 'grayscale brightness-75' : '' }}"
                            >
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center {{ $isSoldOut ? 'grayscale' : '' }}">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Heart Action -->
                        <button 
                            wire:click="removeFromWishlist({{ $favorite->id }})"
                            class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-full flex items-center justify-center text-red-500 shadow-lg hover:bg-red-50 dark:hover:bg-red-900/40 transition-all active:scale-90"
                            title="Remove from wishlist"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>

                        <!-- Date Badge -->
                        <div class="absolute bottom-4 left-4 px-3 py-1.5 bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-xl shadow-sm">
                            <span class="text-xs font-bold text-gray-900 dark:text-gray-100">
                                {{ $event->event_date->format('M d') }}
                            </span>
                        </div>

                        <!-- Sold Out Overlay -->
                        @if($isSoldOut)
                            <div class="absolute inset-0 flex items-center justify-center p-4">
                                <div class="px-6 py-2 bg-red-600 outline outline-4 outline-red-600/30 text-white text-xs font-black uppercase tracking-[0.2em] rounded-full shadow-2xl rotate-[-5deg]">
                                    Sold Out
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Category -->
                        <div class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-2">
                             {{ $event->categories->first()->name ?? 'Event' }}
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 min-h-[3.5rem]">
                            {{ $event->title }}
                        </h3>

                        <!-- Location -->
                        <div class="flex items-center gap-2 text-gray-400 dark:text-gray-500 text-sm mb-6">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">{{ $event->venue_name ?? $event->location }}</span>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-auto flex items-center justify-between gap-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Starting from</span>
                                <span class="text-xl font-black text-gray-900 dark:text-gray-100">
                                    Rp{{ number_format($event->from_price ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($isSoldOut)
                                <button disabled class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-sm font-bold rounded-2xl cursor-not-allowed">
                                    Unavailable
                                </button>
                            @else
                                <a 
                                    href="{{ route('events.show', $event->slug) }}"
                                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-500/20 transition-all active:scale-[0.98]"
                                >
                                    Buy Now
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $this->wishlist->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 p-12 text-center">
            <div class="w-20 h-20 bg-red-50 dark:bg-red-900/20 rounded-3xl flex items-center justify-center text-red-500 mx-auto mb-6 transform rotate-12">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Your wishlist is empty</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-sm mx-auto">Discover upcoming events and save your favorites here to keep track of them.</p>
            <a 
                href="{{ route('events.index') }}" 
                class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-lg shadow-red-500/20 transition-all active:scale-[0.98]"
            >
                Explore Events
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('removed-from-wishlist', (event) => {
        // Option to use a toast if available, otherwise silent or alert
        console.log(event.message);
    });
});
</script>
