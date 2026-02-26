<x-filament-widgets::section>
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Event Results & Analytics</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Browse detailed performance metrics for all published events</p>
        </div>

        @if($events->isEmpty())
            <div class="rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No Published Events</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Publish an event to view its results and analytics</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($events as $event)
                    <a href="{{ \App\Filament\Resources\EventResource::getUrl('results', ['record' => $event]) }}" class="group">
                        <div class="relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm transition-all hover:shadow-lg hover:border-blue-400 dark:hover:border-blue-500">
                            <!-- Header with banner -->
                            <div class="relative h-32 overflow-hidden bg-gradient-to-br from-blue-400 to-blue-600">
                                @if($event->banner?->url)
                                    <img src="{{ $event->banner->url }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <svg class="h-16 w-16 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                    {{ $event->title }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    📅 {{ $event->event_date->format('M d, Y') }} • 📍 {{ $event->city->name ?? $event->location }}
                                </p>

                                <!-- Metrics -->
                                <div class="mt-4 grid grid-cols-3 gap-2 text-center text-xs">
                                    <div class="rounded-lg bg-gray-50 dark:bg-gray-700 p-2">
                                        <div class="font-bold text-gray-900 dark:text-white">
                                            {{ $event->orders->where('status', 'completed')->count() }}
                                        </div>
                                        <div class="text-gray-600 dark:text-gray-400">Orders</div>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 dark:bg-gray-700 p-2">
                                        <div class="font-bold text-gray-900 dark:text-white">
                                            {{ $event->ticketTypes->sum('sold_count') }}
                                        </div>
                                        <div class="text-gray-600 dark:text-gray-400">Tickets</div>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 dark:bg-gray-700 p-2">
                                        <div class="font-bold text-yellow-500">
                                            {{ round($event->testimonials->where('is_published', true)->avg('rating'), 1) ?? '—' }}
                                        </div>
                                        <div class="text-gray-600 dark:text-gray-400">Rating</div>
                                    </div>
                                </div>

                                <!-- Revenue -->
                                <div class="mt-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 p-3">
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Revenue</div>
                                    <div class="mt-1 text-lg font-bold text-blue-600 dark:text-blue-400">
                                        Rp {{ number_format($event->orders->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- View Button -->
                                <button class="mt-4 w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    View Details →
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::section>
