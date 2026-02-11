<div>
    <!-- Hero Banner Section -->
    <section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)"/>
            </svg>
        </div>

        <div class="relative container mx-auto px-4 py-16 md:py-24">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    {{ __('welcome.hero_title') }}
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-primary-100">
                    {{ __('welcome.hero_subtitle') }}
                </p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('welcome.search_placeholder') }}" 
                            class="w-full pl-12 pr-4 py-4 rounded-xl text-gray-900 focus:outline-none focus:ring-4 focus:ring-primary-300 shadow-lg text-lg"
                        >
                        @if($search)
                            <button wire:click="$set('search', '')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold mb-2">500+</div>
                        <div class="text-primary-200 text-sm">{{ __('welcome.stat_events') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold mb-2">50K+</div>
                        <div class="text-primary-200 text-sm">{{ __('welcome.stat_attendees') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold mb-2">100+</div>
                        <div class="text-primary-200 text-sm">{{ __('welcome.stat_organizers') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold mb-2">4.9</div>
                        <div class="text-primary-200 text-sm">{{ __('welcome.stat_rating') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- Promo/Featured Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('welcome.featured_title') }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ __('welcome.featured_subtitle') }}
                </p>
            </div>

            <!-- Featured Events Carousel -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if($featuredEvents && $featuredEvents->count() > 0)
                    @foreach($featuredEvents as $event)
                        <div class="relative group cursor-pointer" wire:click="viewEvent({{ $event->id }})">
                            <div class="aspect-video bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl overflow-hidden shadow-lg group-hover:shadow-2xl transition-all duration-300 transform group-hover:-translate-y-2">
                                @if($event->banner)
                                    <img src="{{ Storage::url($event->banner->file_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white">
                                        <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4">
                                <span class="inline-block px-3 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full mb-2">
                                    {{ __('welcome.featured_badge') }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                    {{ $event->title }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    {{ Str::limit($event->description, 100) }}
                                </p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $event->event_date?->format('M j, Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Placeholder cards if no featured events -->
                    @for($i = 1; $i <= 3; $i++)
                        <div class="relative group">
                            <div class="aspect-video bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl overflow-hidden shadow-lg">
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ __('welcome.featured_placeholder') }} {{ $i }}
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ __('welcome.featured_description') }}
                                </p>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('welcome.categories_title') }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ __('welcome.categories_subtitle') }}
                </p>
            </div>

            @if(isset($categories) && $categories->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    @foreach($categories as $category)
                        <button 
                            wire:click="filterByCategory({{ $category->id }})"
                            class="group bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-2 border-transparent hover:border-primary-500 @if($selectedCategory == $category->id) border-primary-500 @endif"
                        >
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 text-center group-hover:text-primary-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs text-gray-500 text-center mt-1">
                                {{ $category->events_count ?? 0 }} {{ __('welcome.events') }}
                            </p>
                        </button>
                    @endforeach
                    
                    <!-- View All Categories Button -->
                    <button 
                        wire:click="viewAllCategories"
                        class="group bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-white text-center">
                            {{ __('welcome.view_all') }}
                        </h3>
                    </button>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">
                        {{ __('welcome.no_categories') }}
                    </p>
                </div>
            @endif
        </div>
    </section>

    <!-- All Events Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        {{ __('welcome.events_title') }}
                    </h2>
                    <p class="text-gray-600">
                        {{ __('welcome.events_subtitle', ['count' => $events->total()]) }}
                    </p>
                </div>
                
                <!-- Filter Buttons -->
                <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                    <button 
                        wire:click="filterByDate('upcoming')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $dateRange === 'upcoming' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        {{ __('welcome.filter_upcoming') }}
                    </button>
                    <button 
                        wire:click="filterByDate('this_week')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $dateRange === 'this_week' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        {{ __('welcome.filter_this_week') }}
                    </button>
                    <button 
                        wire:click="filterByDate('this_month')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $dateRange === 'this_month' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        {{ __('welcome.filter_this_month') }}
                    </button>
                </div>
            </div>

            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($events as $event)
                        <x-event.checkout-event-card :event="$event" />
                    @endforeach
                </div>

                <!-- Load More Button -->
                @if($events->hasMorePages())
                    <div class="text-center mt-12">
                        <button 
                            wire:click="loadMore"
                            class="px-8 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors shadow-lg hover:shadow-xl"
                        >
                            {{ __('welcome.load_more') }}
                        </button>
                    </div>
                @endif
            @else
                <div class="bg-gray-50 rounded-2xl p-12 text-center">
                    <svg class="mx-auto h-20 w-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        {{ __('welcome.no_events_title') }}
                    </h3>
                    <p class="text-gray-500 mb-6">
                        {{ __('welcome.no_events_description') }}
                    </p>
                    <button 
                        wire:click="resetFilters"
                        class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        {{ __('welcome.reset_filters') }}
                    </button>
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    {{ __('welcome.cta_title') }}
                </h2>
                <p class="text-xl text-primary-100 mb-8">
                    {{ __('welcome.cta_subtitle') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ __('welcome.newsletter_title') }}
                </h2>
                <p class="text-gray-600 mb-8">
                    {{ __('welcome.newsletter_subtitle') }}
                </p>
                <form class="flex flex-col sm:flex-row gap-4">
                    <input 
                        type="email" 
                        placeholder="{{ __('welcome.newsletter_placeholder') }}" 
                        class="flex-1 px-6 py-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                    <button type="submit" class="px-8 py-4 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                        {{ __('welcome.newsletter_subscribe') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
