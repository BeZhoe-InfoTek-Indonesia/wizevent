<div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Welcome to Event Management
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-primary-100">
                Discover amazing events and book your tickets instantly
            </p>
            <div class="max-w-md mx-auto">
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Search events..." 
                    class="w-full px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-300"
                >
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Upcoming Events
            </h2>
            
            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="h-48 bg-gradient-to-r from-event-400 to-event-600"></div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    {{ $event->name ?? 'Sample Event' }}
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    {{ $event->description ?? 'Join us for an amazing experience!' }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">
                                        {{ $event->start_date?->format('M j, Y') ?? 'Coming Soon' }}
                                    </span>
                                    <button class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">
                        No events found matching your search.
                    </p>
                </div>
            @endif
        </div>
    </section>
</div>