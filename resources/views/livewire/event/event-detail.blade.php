<div x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 500)">
    @php
        $bannerImages = $event->banners->isNotEmpty() 
            ? $event->banners->map(fn($b) => $b->getSizeUrl('large') ?? $b->url)->values()->toArray()
            : ($event->banner ? [$event->banner->getSizeUrl('large') ?? $event->banner->url] : []);
    @endphp


    {{-- Hero Section --}}
    <div class="relative w-full overflow-hidden" 
         x-data="{ 
            activeIndex: 0, 
            images: @js($bannerImages),
            next() { this.activeIndex = (this.activeIndex + 1) % this.images.length },
            prev() { this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length }
         }"
    >
        {{-- Vivid Blurred Background --}}
        <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none bg-black">
            <template x-if="images.length > 0">
                <div class="relative w-full h-full">
                    <img :src="images[activeIndex]" 
                         alt="Background Blur" 
                         class="w-full h-full object-cover blur-[150px] opacity-100 scale-125 transition-all duration-1000">
                </div>
            </template>
            {{-- Smooth Vignette Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-black/80"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 py-12 lg:py-16">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-center">
                {{-- Event Poster --}}
                <div class="w-full lg:w-[560px] shrink-0">
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden shadow-2xl relative bg-black">
                        <template x-if="images.length > 0">
                            <div class="relative w-full h-full">
                                <template x-for="(img, index) in images" :key="index">
                                    <img :src="img" 
                                         x-show="activeIndex === index"
                                         x-transition:enter="transition ease-out duration-700"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         class="absolute inset-0 w-full h-full object-cover">
                                </template>
                            </div>
                        </template>
                        
                        {{-- Favorite Button --}}
                        <div class="absolute top-4 right-4 z-20">
                            <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-gray-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Event Info --}}
                <div class="flex-1 space-y-6">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight">
                        {{ $event->title }}
                    </h1>

                    <div class="space-y-4">
                        {{-- Location --}}
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                {{ $event->venue_name ?? $event->location }}
                            </p>
                        </div>

                        {{-- Date --}}
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-300 text-sm">
                                {{ $event->event_date?->format('l, d F Y') }}
                                @if($event->event_end_date)
                                     - {{ $event->event_end_date->format('l, d F Y') }}
                                @endif
                            </p>
                        </div>

                        {{-- Price Hint --}}
                        @php $minPrice = $this->ticketTypes->min('price'); @endphp
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            <p class="text-gray-300 text-sm">
                                Ticket prices start from <span class="text-white ml-1">IDR {{ number_format($minPrice ?? 0) }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Action Bar --}}
                    <div class="mt-8 bg-black/40 backdrop-blur-md rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 max-w-xl">
                        <p class="text-gray-300 text-md font-medium">Available now, secure your spot!</p>
                        <a href="#tickets-section"
                           class="w-full sm:w-auto min-w-[200px] bg-[#1A8DFF] hover:bg-blue-600 text-white font-bold py-4 px-10 rounded-xl transition-all text-base text-center shadow-lg hover:shadow-xl hover:scale-105">
                            Buy ticket now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-white">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-col lg:flex-row gap-12">
                
                {{-- Left Content --}}
                <div class="flex-1 space-y-12">
                    
                    {{-- Row: Event Details & Ticket Selection --}}
                    <div class="flex flex-col xl:flex-row gap-12">
                        
                        <div class="flex-1 space-y-12">
                            <section>
                                <h2 class="text-xl font-bold text-gray-900 mb-6">Event Details</h2>
                                
                                {{-- Description --}}
                                <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                                    {!! $event->description !!}
                                </div>
                            </section> 
                            
                            {{-- Location Section --}}
                            <section class="border-t border-gray-100 pt-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-6">Location</h2>
                                <div class="border border-gray-100 rounded-xl overflow-hidden">
                                    <div class="h-48 w-full relative bg-gray-100">
                                        @if($event->latitude && $event->longitude)
                                            <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                                                    src="https://maps.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}&hl=en&z=15&output=embed" 
                                                    allowfullscreen class="grayscale"></iframe>
                                        @endif
                                    </div>
                                    <div class="p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                                        <div class="flex items-start gap-3 flex-1">
                                            <svg class="w-5 h-5 text-gray-400 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700 font-medium">{{ $event->location }}</span>
                                        </div>
                                        <div class="flex gap-4">
                                            <a href="https://maps.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}" target="_blank"
                                               class="flex items-center gap-2 text-[#1A8DFF] text-xs font-bold hover:underline">
                                                <div class="w-8 h-8 rounded-full bg-[#E5F3FF] flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                                </div>
                                                View Map
                                            </a>
                                            <button class="flex items-center gap-2 text-[#1A8DFF] text-xs font-bold hover:underline">
                                                <div class="w-8 h-8 rounded-full bg-[#E5F3FF] flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M21.71 11.29l-9-9a.996.996 0 0 0-1.41 0l-9 9a.996.996 0 0 0 0 1.41l9 9c.39.39 1.02.39 1.41 0l9-9a.996.996 0 0 0 0-1.41zM14 14.5V12h-4v3H8v-4c0-.55.45-1 1-1h5V7.5L18.5 11 14 14.5z"/></svg>
                                                </div>
                                                How to Get There
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Right Side: Tickets Sidebar --}}
                        <div class="w-full lg:w-[400px] shrink-0" id="tickets-section">
                            <div class="sticky top-12 space-y-6">
                                <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden">
                                    {{-- Available now bar integrated into sidebar --}}
                                    <div x-show="scrolled" x-transition:enter="transition ease-in-out duration-500" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 bg-white border-b border-gray-50 flex items-center justify-between gap-4">
                                        <p class="text-gray-500 text-[13px] font-medium leading-tight max-w-[130px]">Available now, secure your spot!</p>
                                        <a href="#tickets-section" class="bg-[#1A8DFF] hover:bg-blue-600 text-white font-bold py-4 px-5 rounded-xl transition-all text-sm whitespace-nowrap shadow-md hover:shadow-lg hover:scale-105">
                                            Buy ticket now
                                        </a>
                                    </div>

                                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                        <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[14px]">Category</h3>
                                        <h3 class="font-bold text-gray-900 uppercase tracking-widest text-[14px]">Price</h3>
                                    </div>
                                    <div class="p-8 space-y-6">
                                        @if($this->ticketTypes->isNotEmpty())
                                            @foreach($this->ticketTypes->sortBy('price') as $ticket)
                                                <div class="flex justify-between items-center group">
                                                    <div class="space-y-1">
                                                        <h4 class="font-bold text-gray-800 text-sm">{{ $ticket->name }}</h4>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-black text-gray-900">IDR {{ number_format($ticket->price) }}</p>
                                                    </div>
                                                </div>
                                                @if(!$loop->last) <div class="h-px bg-gray-50 w-full"></div> @endif
                                            @endforeach
                                        @else
                                            <p class="text-center text-gray-400 text-sm py-4">No tickets available at the moment.</p>
                                        @endif
                                        
                                        <div class="pt-6 border-t border-gray-100 flex items-start gap-3">
                                            <svg class="w-4 h-4 text-gray-400 shrink-0 translate-y-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-[10px] text-gray-500 leading-relaxed">
                                                Convenience fee, taxes, and other applicable charges will be added at checkout.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recommendations Section --}}
                    @if($this->relatedEvents->isNotEmpty())
                    <section class="border-t border-gray-100 pt-16">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-xl font-bold text-gray-900">You Might Also Like This</h2>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 overflow-hidden">
                            @foreach($this->relatedEvents->take(5) as $rel)
                                <a href="{{ route('events.show', $rel->slug) }}" class="group block space-y-3">
                                    <div class="aspect-[16/10] rounded-xl overflow-hidden bg-gray-100 shadow-sm">
                                        @if($rel->banner)
                                            <img src="{{ Str::startsWith($rel->banner->url, 'http') ? $rel->banner->url : Storage::url($rel->banner->file_path) }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @endif
                                    </div>
                                    <div class="space-y-1 px-1">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $rel->event_date?->format('d M Y') }}</p>
                                        <h4 class="font-bold text-gray-900 text-xs line-clamp-2 leading-snug group-hover:text-[#1A8DFF] transition-colors">{{ $rel->title }}</h4>
                                        <p class="text-[10px] text-gray-500 font-medium truncate">{{ $rel->location }}</p>
                                        <p class="text-xs font-bold text-red-500 pt-1">IDR {{ number_format($rel->ticketTypes->min('price') ?? 0) }}</p>
                                    </div>
                                </a>
                            @endforeach
                            
                            {{-- Scroll Arrow Placeholder or Real Arrow --}}
                            <div class="absolute right-0 hidden lg:flex items-center justify-center p-2">
                                <button class="w-10 h-10 bg-white rounded-full shadow-lg border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                        </div>
                    </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
