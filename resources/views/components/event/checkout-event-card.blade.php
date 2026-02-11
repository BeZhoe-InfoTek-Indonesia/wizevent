@props(['event'])

<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
    <a href="{{ route('events.show', $event->slug) }}" class="block">
        <div class="relative h-48 bg-gray-200">
            @if($event->banner)
                <img
                    src="{{ $event->banner->getSizeUrl('medium') ?? $event->banner->url }}"
                    alt="{{ $event->title }}"
                    class="w-full h-full object-cover"
                >
            @else
                <div class="w-full h-full bg-gradient-to-r from-primary-400 to-primary-600"></div>
            @endif

            @if($event->is_sold_out)
                <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                    Sold Out
                </span>
            @endif
        </div>

        <div class="p-5 flex flex-col flex-grow">
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                {{ $event->title }}
            </h3>

            <div class="space-y-2 mb-4 flex-grow">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $event->event_date->format('M j, Y \a\t g:i A') }}</span>
                </div>

                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="truncate">{{ $event->venue_name ?? $event->location }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div>
                    @if($event->from_price !== null)
                        <span class="text-sm text-gray-500">From</span>
                        <span class="text-lg font-bold text-primary-600">${{ number_format($event->from_price, 2) }}</span>
                    @else
                        <span class="text-sm text-gray-500">Free</span>
                    @endif
                </div>

                <span class="text-primary-600 font-semibold text-sm group-hover:text-primary-700">
                    View Details
                </span>
            </div>
        </div>
    </a>
</div>
