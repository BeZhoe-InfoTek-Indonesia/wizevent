@extends('layouts.profile-layout')

@push('scripts')
    @filamentScripts(withCore: true)
@endpush

@section('profile-content')
    <div class="profile-content space-y-6">

    <!-- Dashboard Tab Content -->
    <div x-show="activeTab === 'dashboard'" x-transition role="tabpanel" id="panel-dashboard" aria-labelledby="tab-dashboard" tabindex="0" x-effect="if (activeTab === 'dashboard') { centerTabContent('dashboard') }">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 md:p-6 mb-6 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-2">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('profile.dashboard') }}</h1>
                    <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 mt-1">{{ __('profile.welcome_back', ['name' => explode(' ', $user->name)[0], 'count' => $upcomingEvents->count()]) }}</p>
                </div>
                <span class="text-xs sm:text-sm text-gray-500">{{ now()->format('M d, Y') }}</span>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <!-- Active Tickets -->
                <div @click="activeTab = 'orders'" class="cursor-pointer bg-red-50 dark:bg-red-900/20 p-4 rounded-xl flex flex-col items-center justify-center hover:shadow-md transition-all duration-200 hover:scale-105 hover:-translate-y-1 transform active:scale-95">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center mb-2 text-red-600 dark:text-red-200 transition-transform duration-200 hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $ticketsCount }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ __('profile.active_tickets') }}</span>
                </div>
                <!-- Total Orders -->
                <div @click="activeTab = 'orders'" class="cursor-pointer bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl flex flex-col items-center justify-center hover:shadow-md transition-all duration-200 hover:scale-105 hover:-translate-y-1 transform active:scale-95">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mb-2 text-blue-600 dark:text-blue-200 transition-transform duration-200 hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $historyCount }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ __('profile.total_orders') }}</span>
                </div>
                <!-- Next Event -->
                <div @click="activeTab = 'dashboard'" class="cursor-pointer bg-green-50 dark:bg-green-900/20 p-4 rounded-xl flex flex-col items-center justify-center hover:shadow-md transition-all duration-200 hover:scale-105 hover:-translate-y-1 transform active:scale-95">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mb-2 text-green-600 dark:text-green-200 transition-transform duration-200 hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-gray-100 text-center line-clamp-1">{{ $upcomingEvents->first()?->title ?? __('profile.none') }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ __('profile.next_event') }}</span>
                </div>
                <!-- Wishlist -->
                <div @click="activeTab = 'wishlist'" class="cursor-pointer bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl flex flex-col items-center justify-center hover:shadow-md transition-all duration-200 hover:scale-105 hover:-translate-y-1 transform active:scale-95">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-800 rounded-full flex items-center justify-center mb-2 text-purple-600 dark:text-purple-200 transition-transform duration-200 hover:scale-110">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $wishlistCount }}</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ __('profile.wishlist') }}</span>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                    <h3 class="font-bold text-base md:text-lg text-gray-900 dark:text-gray-100">{{ __('profile.upcoming_events') }}</h3>
                    <a href="{{ route('events.index') }}" class="text-xs sm:text-sm text-red-500 hover:text-red-600 font-medium">{{ __('profile.view_all') }}</a>
                </div>
                @if($upcomingEvents->count() > 0)
                    @php $upcomingNeedsSlider = $upcomingEvents->count() > 2; @endphp
                    <div class="relative" x-data="{}">
                        <div x-ref="upcomingSlider" class="{{ $upcomingNeedsSlider ? 'flex flex-nowrap gap-3 md:gap-4 overflow-x-auto p-2 pb-4 -mx-2 scrollbar-hide snap-x snap-mandatory w-[calc(100%+1rem)]' : 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4' }}">
                            @foreach($upcomingEvents as $event)
                                <div class="{{ $upcomingNeedsSlider ? 'min-w-[85vw] md:min-w-[300px] snap-start shrink-0' : '' }} flex gap-3 md:gap-4 bg-white dark:bg-gray-700/50 rounded-xl p-3 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all">
                                    <img src="{{ $event->banner?->url ?? 'https://placehold.co/100x100' }}" alt="{{ $event->title }}" class="w-16 h-16 md:w-20 md:h-20 rounded-lg object-cover shrink-0">
                                    <div class="flex flex-col justify-center min-w-0 flex-1">
                                        <h4 class="font-bold text-sm md:text-base text-gray-900 dark:text-gray-100 line-clamp-1">{{ $event->title }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <span class="block mb-1">📅 {{ $event->event_date->format('M d • H:i') }}</span>
                                            <span class="line-clamp-1">📍 {{ $event->venue_name ?? $event->location ?? 'TBA' }}</span>
                                        </p>
                                        @if($event->status === 'published')
                                            <span class="inline-block mt-2 text-xs text-green-600 font-medium bg-green-50 dark:bg-green-900/20 dark:text-green-200 px-2 py-0.5 rounded-full w-fit">{{ __('profile.confirmed') }}</span>
                                        @else
                                            <span class="inline-block mt-2 text-xs text-yellow-600 font-medium bg-yellow-50 dark:bg-yellow-900/20 dark:text-yellow-200 px-2 py-0.5 rounded-full w-fit">{{ __('profile.processing') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col justify-between items-end shrink-0 ml-2">
                                       <span class="font-bold text-sm md:text-base text-red-500">
                                            @if(($event->from_price ?? 0) == 0)
                                                {{ __('profile.free') }}
                                            @else
                                                {{ __('profile.from_price', ['price' => number_format($event->from_price, 2)]) }}
                                            @endif
                                       </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($upcomingNeedsSlider)
                            <div class="hidden md:flex absolute inset-y-0 left-0 items-center z-10">
                                <button type="button" class="ml-[-18px] w-9 h-9 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-center hover:scale-110 transition-transform" aria-label="{{ __('profile.previous_slide') }}" @click="$refs.upcomingSlider.scrollBy({ left: -320, behavior: 'smooth' })">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="hidden md:flex absolute inset-y-0 right-0 items-center z-10">
                                <button type="button" class="mr-[-18px] w-9 h-9 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-center hover:scale-110 transition-transform" aria-label="{{ __('profile.next_slide') }}" @click="$refs.upcomingSlider.scrollBy({ left: 320, behavior: 'smooth' })">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                        <p class="text-gray-500">{{ __('profile.no_upcoming_events') }}</p>
                        <a href="{{ route('events.index') }}" class="text-red-500 hover:underline text-sm font-medium mt-2">{{ __('profile.browse_events') }}</a>
                    </div>
                @endif
            </div>

            <!-- Featured For You -->
            <div>
                <h3 class="font-bold text-base md:text-lg text-gray-900 dark:text-gray-100 mb-4">{{ __('profile.featured_for_you') }}</h3>
                @php $featuredNeedsSlider = $featuredEvents->count() > 2; @endphp
                <div class="relative" x-data="{}">
                    <div x-ref="featuredSlider" class="{{ $featuredNeedsSlider ? 'flex flex-nowrap gap-3 md:gap-4 overflow-x-auto p-2 pb-4 -mx-2 scrollbar-hide snap-x snap-mandatory w-[calc(100%+1rem)]' : 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4' }}">
                        @foreach($featuredEvents as $event)
                            <div class="{{ $featuredNeedsSlider ? 'min-w-[75vw] md:min-w-[280px] snap-start shrink-0' : '' }} bg-white dark:bg-gray-700/30 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all group">
                                <div class="relative">
                                    <img src="{{ $event->banner?->url ?? 'https://placehold.co/400x200' }}" alt="{{ $event->title }}" class="w-full h-28 md:h-32 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <button type="button" class="absolute top-2 right-2 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-3">
                                    <h4 class="font-bold text-sm md:text-base text-gray-900 dark:text-gray-100 line-clamp-1">{{ $event->title }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 line-clamp-1">{{ $event->event_date->format('M d') }} • {{ $event->location ?? 'Online' }}</p>
                                    <p class="text-red-500 font-bold text-sm">
                                        @if(($event->from_price ?? 0) == 0)
                                            {{ __('profile.free') }}
                                        @else
                                            {{ __('profile.from_price', ['price' => number_format($event->from_price, 2)]) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($featuredNeedsSlider)
                        <div class="hidden md:flex absolute inset-y-0 left-0 items-center z-10">
                            <button type="button" class="ml-[-18px] w-9 h-9 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-center hover:scale-110 transition-transform" aria-label="{{ __('profile.previous_slide') }}" @click="$refs.featuredSlider.scrollBy({ left: -320, behavior: 'smooth' })">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        </div>
                        <div class="hidden md:flex absolute inset-y-0 right-0 items-center z-10">
                            <button type="button" class="mr-[-18px] w-9 h-9 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-center hover:scale-110 transition-transform" aria-label="{{ __('profile.next_slide') }}" @click="$refs.featuredSlider.scrollBy({ left: 320, behavior: 'smooth' })">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Account Tab Content -->
    <div x-show="activeTab === 'account'" x-transition role="tabpanel" id="panel-account" aria-labelledby="tab-account" tabindex="0" x-effect="if (activeTab === 'account') { centerTabContent('account') }">
        <div class="mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('profile.account_settings') }}</h2>
            <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 mt-1">{{ __('profile.manage_profile_preferences') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 md:p-6 border border-gray-100 dark:border-gray-700">
                    <x-profile.profile-form />
                </div>

                <!-- Password -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 md:p-6 border border-gray-100 dark:border-gray-700">
                    <x-profile.password-section />
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Avatar -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 md:p-6 border border-gray-100 dark:border-gray-700">
                    <x-profile.avatar-section :user="$user" />
                </div>

                <!-- Member Since -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 md:p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 md:gap-4">
                        <div class="p-2 md:p-3 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-xl">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('profile.member_since') }}</p>
                            <p class="text-lg md:text-xl font-bold text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Tab Content -->
    <div x-show="activeTab === 'reviews'" x-transition role="tabpanel" id="panel-reviews" aria-labelledby="tab-reviews" tabindex="0" x-effect="if (activeTab === 'reviews') { centerTabContent('reviews') }">
        <livewire:profile.your-reviews />
    </div>

    <!-- Wishlist Tab Content -->
    <div x-show="activeTab === 'wishlist'" x-transition role="tabpanel" id="panel-wishlist" aria-labelledby="tab-wishlist" tabindex="0" x-effect="if (activeTab === 'wishlist') { centerTabContent('wishlist') }">
        <livewire:profile.wishlist />
    </div>

    <!-- Orders Tab Content -->
    <div x-show="activeTab === 'orders'" x-transition role="tabpanel" id="panel-orders" aria-labelledby="tab-orders" tabindex="0" x-effect="if (activeTab === 'orders') { centerTabContent('orders') }">
        <livewire:profile.your-orders />
    </div>

    <!-- Help Center Tab Content -->
    <div x-show="activeTab === 'help'" x-transition role="tabpanel" id="panel-help" aria-labelledby="tab-help" tabindex="0" x-effect="if (activeTab === 'help') { centerTabContent('help') }">
        <livewire:profile.help-center />
    </div>

    <!-- Settings Tab Content -->
    <div x-show="activeTab === 'settings'" x-transition role="tabpanel" id="panel-settings" aria-labelledby="tab-settings" tabindex="0" x-effect="if (activeTab === 'settings') { centerTabContent('settings') }">
        <livewire:profile.settings />
    </div>
    </div>

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('profile-updated', (event) => {
            alert(event.message);
        });
        Livewire.on('password-updated', (event) => {
            alert(event.message);
        });
        Livewire.on('avatar-updated', (event) => {
            alert(event.message);
            window.location.reload();
        });
        Livewire.on('avatar-deleted', (event) => {
            alert(event.message);
            window.location.reload();
        });
    });
    // Center focus to content tab when tab is clicked
    document.addEventListener('alpine:init', () => {
        window.centerTabContent = function(tabName) {
            setTimeout(() => {
                const panel = document.getElementById('panel-' + tabName);
                if (panel) {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    panel.focus({ preventScroll: true });
                }
            }, 50);
        };
    });
    </script>
@endsection
