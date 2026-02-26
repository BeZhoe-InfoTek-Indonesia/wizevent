<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-6xl mx-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Loved Events</h1>
                    <p class="text-gray-600">Events you've saved for later</p>
                </div>
            </div>

            @if($this->lovedEvents->isEmpty())
                {{-- Empty State --}}
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No loved events yet</h3>
                    <p class="text-gray-600 mb-6">Start exploring and save events you're interested in!</p>
                    <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#1A8DFF] to-[#0066CC] text-white rounded-xl text-sm font-bold hover:shadow-lg hover:scale-105 transition-all">
                        Browse Events
                    </a>
                </div>
            @else
                {{-- Events Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($this->lovedEvents as $event)
                        <div class="group bg-white border border-gray-100 rounded-[24px] overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] transition-all duration-500">
                            {{-- Image --}}
                            <a href="{{ route('events.show', $event->slug) }}" class="block relative aspect-[16/10] overflow-hidden">
                                @if($event->banner)
                                    <img src="{{ $event->banner->getSizeUrl('large') ?? $event->banner->url }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Remove Button --}}
                                <button wire:click="removeFavorite({{ $event->id }})"
                                        class="absolute top-4 right-4 z-10 w-10 h-10 bg-white/90 backdrop-blur-md rounded-xl flex items-center justify-center shadow-lg hover:scale-110 transition-all group/btn">
                                    <svg class="w-5 h-5 text-red-500 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            </a>

                            {{-- Content --}}
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-2 py-1 bg-blue-50 text-[#1A8DFF] text-[10px] font-black uppercase tracking-widest rounded-md">
                                        {{ $event->event_date?->format('M j') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                        {{ $event->location }}
                                    </span>
                                </div>

                                <a href="{{ route('events.show', $event->slug) }}" class="block">
                                    <h3 class="font-black text-gray-900 text-lg line-clamp-2 leading-snug mb-3 group-hover:text-[#1A8DFF] transition-colors">
                                        {{ $event->title }}
                                    </h3>
                                </a>

                                @if($event->ticketTypes->isNotEmpty())
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                        <p class="text-sm font-black text-gray-900">
                                            Rp {{ number_format($event->ticketTypes->min('price'), 0, ',', '.') }}
                                        </p>
                                        <a href="{{ route('events.show', $event->slug) }}" class="text-xs font-bold text-[#1A8DFF] hover:underline">
                                            View Details
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <x-footer />
</div>
