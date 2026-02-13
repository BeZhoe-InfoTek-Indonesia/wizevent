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
            autoSlideInterval: null,
            init() {
                if (this.images.length > 1) {
                    this.startAutoSlide();
                }
            },
            startAutoSlide() {
                this.autoSlideInterval = setInterval(() => {
                    this.next();
                }, 5000);
            },
            stopAutoSlide() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                    this.autoSlideInterval = null;
                }
            },
            next() {
                this.activeIndex = (this.activeIndex + 1) % this.images.length;
            },
            prev() {
                this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
            }
         }"
    >
        {{-- Vivid Blurred Background --}}
        <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none bg-black">
            <template x-if="images.length > 0">
                <div class="relative w-full h-full">
                    <template x-for="(img, index) in images" :key="'bg-'+index">
                        <img :src="img" 
                             x-show="activeIndex === index"
                             x-transition:enter="transition ease-out duration-1000"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             alt="Background Blur" 
                             class="absolute inset-0 w-full h-full object-cover blur-[160px] opacity-100 scale-125">
                    </template>
                </div>
            </template>
            {{-- Smooth Vignette Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/80"></div>
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
                        
                        {{-- Previous Button --}}
                        <button x-show="images.length > 1"
                                @click="prev(); stopAutoSlide(); startAutoSlide();"
                                class="absolute left-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 hover:bg-white/20 transition-all group/btn">
                            <svg class="w-6 h-6 text-white group-hover/btn:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        
                         {{-- Favorite Button --}}
                         <div class="absolute top-6 right-6 z-20">
                             <button wire:click="toggleFavorite"
                                     class="w-12 h-12 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 hover:bg-white/20 transition-all text-white group/fav">
                                 @if($this->isFavorited)
                                     <svg class="w-6 h-6 text-red-500 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                         <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                     </svg>
                                 @else
                                     <svg class="w-6 h-6 transition-colors group-hover/fav:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                     </svg>
                                 @endif
                             </button>
                         </div>
                        
                        {{-- Next Button --}}
                        <button x-show="images.length > 1"
                                @click="next(); stopAutoSlide(); startAutoSlide();"
                                class="absolute right-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 hover:bg-white/20 transition-all group/btn">
                            <svg class="w-6 h-6 text-white group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        {{-- Dots Indicator --}}
                        <div x-show="images.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                            <template x-for="(img, index) in images" :key="index">
                                <button @click="activeIndex = index; stopAutoSlide(); startAutoSlide();"
                                        class="w-2 h-2 rounded-full transition-all"
                                        :class="activeIndex === index ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/70'">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Event Info --}}
                <div class="flex-1 space-y-8">
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4">
                            <span class="px-3 py-1 bg-blue-500/20 backdrop-blur-md rounded-full text-blue-300 text-[10px] font-bold uppercase tracking-widest border border-blue-500/30">
                                Recommended
                            </span>
                            @if($event->category)
                            <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-white/80 text-[10px] font-bold uppercase tracking-widest border border-white/10">
                                {{ $event->category->name }}
                            </span>
                            @endif
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-white leading-[1.1] tracking-tight">
                            {{ $event->title }}
                        </h1>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Location --}}
                        <div class="group flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all duration-300">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Venue</p>
                                <p class="text-gray-200 text-sm font-semibold leading-tight line-clamp-1">
                                    {{ $event->venue_name ?? $event->location }}
                                </p>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="group flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all duration-300">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Date</p>
                                <p class="text-gray-200 text-sm font-semibold leading-tight">
                                    {{ $event->event_date?->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Bar --}}
                    <div class="flex flex-col sm:flex-row items-center gap-6 pt-4">
                        <a href="{{ route('events.checkout', $event->slug) }}"
                           class="group relative inline-flex items-center justify-center w-full sm:w-auto overflow-hidden rounded-2xl p-0.5 font-bold transition-all duration-300 hover:scale-105 active:scale-95 shadow-[0_0_40px_rgba(26,141,255,0.3)]">
                            <span class="absolute inset-0 bg-gradient-to-r from-[#1A8DFF] to-[#0066CC]"></span>
                            <span class="relative w-full sm:w-auto min-w-[240px] px-10 py-5 bg-[#1A8DFF] rounded-[14px] text-white flex items-center justify-center gap-3 group-hover:bg-transparent transition-all">
                                <span class="text-lg">Get Tickets Now</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </a>
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 rounded-full border-2 border-black bg-gray-800 flex items-center justify-center text-[10px] font-bold text-white">JD</div>
                                <div class="w-8 h-8 rounded-full border-2 border-black bg-blue-600 flex items-center justify-center text-[10px] font-bold text-white">AS</div>
                                <div class="w-8 h-8 rounded-full border-2 border-black bg-purple-600 flex items-center justify-center text-[10px] font-bold text-white">
                                    {{ $event->sold_tickets > 0 ? number_format($event->sold_tickets) . '+' : '+1k' }}
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 font-medium tracking-tight">{{ __('event.attendees') }}</p>
                        </div>
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
                            <section class="relative">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-1.5 h-8 bg-[#1A8DFF] rounded-full"></div>
                                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">About This Event</h2>
                                </div>
                                
                                {{-- Description --}}
                                <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed prose-headings:text-gray-900 prose-headings:font-black prose-a:text-[#1A8DFF] prose-img:rounded-3xl prose-img:shadow-2xl">
                                    {!! $event->description !!}
                                </div>
                            </section> 
                            
                            {{-- Location Section --}}
                            <section class="border-t border-gray-100 pt-12">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-1.5 h-8 bg-purple-500 rounded-full"></div>
                                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Location & Directions</h2>
                                </div>

                                <div class="group bg-white border border-gray-100 rounded-[32px] overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.03)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] transition-all duration-500">
                                    <div class="h-80 w-full relative bg-gray-50 overflow-hidden">
                                        @if($event->latitude && $event->longitude)
                                            <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                                                    src="https://maps.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}&hl=en&z=15&output=embed" 
                                                    allowfullscreen class="grayscale group-hover:grayscale-0 transition-all duration-700"></iframe>
                                        @endif
                                        {{-- Overlay gradient to blend map --}}
                                        <div class="absolute inset-0 pointer-events-none border-[12px] border-white/20 rounded-[32px]"></div>
                                    </div>
                                    <div class="p-8 flex flex-col md:flex-row items-center justify-between gap-8">
                                        <div class="flex items-start gap-4 flex-1">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-blue-500 border border-gray-100 shrink-0">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Venue Address</p>
                                                <span class="text-gray-800 font-bold leading-relaxed">{{ $event->location }}</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-4 w-full md:w-auto">
                                          <a href="https://www.google.com/maps/dir/?api=1&destination={{ $event->latitude }},{{ $event->longitude }}" 
                                           target="_blank"
                                           class="flex items-center justify-center gap-2 px-5 py-3 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-black hover:scale-105 transition-all shadow-lg active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ __('event.get_directions') }}
                                            </a>                                            
                                        </div>
                                    </div>
                                    {{-- Share Section --}}
                                    <div class="pt-6 border-t border-gray-100 px-3 pb-6">
                                        <div class="flex items-center justify-between gap-4">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('event.share_event') }}</p>
                                            
                                            <div class="flex items-center gap-3">
                                                {{-- Copy Link --}}
                                                <button x-data="{
                                                        copied: false,
                                                        copyUrl() {
                                                            const url = '{{ route('events.show', $event->slug) }}';
                                                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                                                navigator.clipboard.writeText(url).then(() => {
                                                                    this.copied = true;
                                                                    setTimeout(() => this.copied = false, 2000);
                                                                }).catch(() => {
                                                                    this.fallbackCopy(url);
                                                                });
                                                            } else {
                                                                this.fallbackCopy(url);
                                                            }
                                                        },
                                                        fallbackCopy(url) {
                                                            const textArea = document.createElement('textarea');
                                                            textArea.value = url;
                                                            textArea.style.position = 'fixed';
                                                            textArea.style.left = '-999999px';
                                                            textArea.style.top = '0';
                                                            document.body.appendChild(textArea);
                                                            textArea.focus();
                                                            textArea.select();
                                                            try {
                                                                document.execCommand('copy');
                                                                this.copied = true;
                                                                setTimeout(() => this.copied = false, 2000);
                                                            } catch (err) {
                                                                console.error('Copy failed:', err);
                                                            }
                                                            document.body.removeChild(textArea);
                                                        }
                                                    }"
                                                    @click="copyUrl()"
                                                    class="group flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                                    <svg x-show="!copied" class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                                    </svg>
                                                    <svg x-show="copied" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="text-xs font-medium text-gray-500 group-hover:text-gray-700" x-text="copied ? 'Copied' : 'Copy'"></span>
                                                </button>

                                                <div class="w-px h-4 bg-gray-200"></div>

                                                 {{-- Social Icons --}}
                                                 <div class="flex items-center gap-1">
                                                     <a href="{{ $this->calendarUrl }}"
                                                        download
                                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#1A8DFF] hover:bg-blue-50 transition-all duration-200">
                                                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                         </svg>
                                                     </a>
                                                     <a href="{{ $this->shareUrls['whatsapp'] ?? '' }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#25D366] hover:bg-green-50 transition-all duration-200">
                                                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                             <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-4.821 4.754a8.117 8.117 0 01-3.834-.963l-.275-.163-2.851.748.761-2.779-.179-.285C5.467 14.71 4.88 13.064 4.88 11.31c0-4.437 3.613-8.05 8.05-8.05 2.15 0 4.17.837 5.69 2.357 1.52 1.52 2.357 3.54 2.357 5.694 0 4.438-3.612 8.051-8.049 8.051"/>
                                                         </svg>
                                                     </a>
                                                     <a href="{{ $this->shareUrls['facebook'] ?? '' }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#1877F2] hover:bg-blue-50 transition-all duration-200">
                                                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                             <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                         </svg>
                                                     </a>
                                                     <a href="{{ $this->shareUrls['twitter'] ?? '' }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:text-black hover:bg-gray-100 transition-all duration-200">
                                                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                             <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                         </svg>
                                                     </a>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- closes share section --}}
                                </div>
                            </section>
                        </div>

                        {{-- Right Side: Tickets Sidebar --}}
                        <div class="w-full lg:w-[420px] shrink-0" id="tickets-section">
                            <div class="sticky top-12 space-y-6">
                                <div class="bg-white rounded-[32px] border border-gray-100 shadow-[0_30px_100px_rgba(0,0,0,0.08)] overflow-hidden">
                                    {{-- Available now bar integrated into sidebar --}}
                                    <div x-show="scrolled"
                                         x-transition:enter="transition ease-out duration-500"
                                         x-transition:enter-start="opacity-0 -translate-y-4"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="p-6 bg-[#1A8DFF]/5 border-b border-gray-50 flex items-center justify-between gap-4">
                                        <div class="space-y-0.5">
                                            <p class="text-gray-900 font-bold text-sm">Tickets are selling fast!</p>
                                            <p class="text-gray-500 text-[11px] leading-tight">Select your category below</p>
                                        </div>
                                        <a href="{{ route('events.checkout', $event->slug) }}"
                                           class="flex items-center justify-center gap-2 px-5 py-3 bg-[#1A8DFF] text-white rounded-xl text-xs font-bold hover:bg-blue-600 hover:scale-105 transition-all shadow-lg active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path>
                                            </svg>
                                            Buy Ticket Now
                                        </a>
                                    </div>

                                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                                        <h3 class="font-black text-gray-900 uppercase tracking-[0.2em] text-[11px]">Ticket Categories</h3>
                                    </div>
                                    
                                    <div class="p-8 space-y-4">
                                        @if($this->ticketTypes->isNotEmpty())
                                            @foreach($this->ticketTypes->sortBy('price') as $ticket)
                                                <div class="group relative p-5 rounded-2xl border-2 border-gray-50 hover:border-blue-500/20 hover:bg-blue-50/30 transition-all duration-300">
                                                    <div class="flex justify-between items-start">
                                                        <div class="space-y-1">
                                                            <h4 class="font-bold text-gray-900 text-[15px] group-hover:text-blue-600 transition-colors">{{ $ticket->name }}</h4>
                                                            <p class="text-[11px] text-gray-500 font-medium">Standard Admission</p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-black text-gray-900 text-lg tracking-tight">IDR {{ number_format($ticket->price) }}</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-12 space-y-4">
                                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto">
                                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                                </div>
                                                <p class="text-gray-400 text-sm font-medium px-4">Sold out or no tickets available yet.</p>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-8 pt-6 border-t border-gray-100 p-4 bg-gray-50/50 rounded-2xl">
                                            <div class="flex gap-3">
                                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm shrink-0">
                                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-[10px] text-gray-400 leading-relaxed">
                                                    Prices include convenience fee and tax. Secure payment powered by Midtrans.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                                
                                </div> {{-- closes sticky top-12 --}}
                            </div> {{-- closes lg:w-[420px] --}}
                        </div> {{-- closes Detail/Sidebar row --}}

                    {{-- Testimonials Section --}}
                    @if($this->approvedTestimonials->isNotEmpty())
                    <section class="border-t border-gray-100 pt-16">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-4">
                                <div class="w-1.5 h-8 bg-yellow-500 rounded-full"></div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">What People Say</h2>
                            </div>
                            <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-xs font-bold uppercase tracking-widest rounded-full">
                                {{ $this->approvedTestimonials->count() }} Reviews
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            @foreach($this->approvedTestimonials as $testimonial)
                                <div class="bg-white border border-gray-100 rounded-[24px] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)] transition-all duration-500">
                                    <div class="flex items-start justify-between gap-4 mb-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $testimonial->user->name }}</h4>
                                                <div class="flex items-center gap-1 mt-0.5">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">
                                            {{ $testimonial->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <p class="text-gray-600 leading-relaxed mb-6">
                                        "{{ $testimonial->content }}"
                                    </p>

                                    <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Was this helpful?</span>
                                            <div class="flex items-center gap-1">
                                                <button wire:click="voteOnTestimonial({{ $testimonial->id }}, true)"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-500 hover:text-green-600 hover:bg-green-50 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                    </svg>
                                                    <span>{{ $testimonial->helpful_votes_count }}</span>
                                                </button>
                                                <button wire:click="voteOnTestimonial({{ $testimonial->id }}, false)"
                                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-500 hover:text-red-600 hover:bg-red-50 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path>
                                                    </svg>
                                                    <span>{{ $testimonial->not_helpful_votes_count }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                    @endif

                    {{-- Testimonial Submission Form --}}
                    @if($this->canSubmitTestimonial && !$this->showTestimonialForm)
                    <section class="border-t border-gray-100 pt-8 mb-8">
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-[24px] p-8 border border-blue-100">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg mb-1">Share Your Experience</h3>
                                    <p class="text-gray-600 text-sm">Attended this event? Help others by leaving a review.</p>
                                </div>
                                <button wire:click="$set('showTestimonialForm', true)"
                                        class="shrink-0 px-6 py-3 bg-gradient-to-r from-[#1A8DFF] to-[#0066CC] text-white rounded-xl text-sm font-bold hover:shadow-lg hover:scale-105 transition-all">
                                    Write a Review
                                </button>
                            </div>
                        </div>
                    </section>
                    @endif

                    @if($this->showTestimonialForm)
                    <section class="border-t border-gray-100 pt-12 mb-12">
                        <div class="bg-white border border-gray-100 rounded-[24px] p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)]">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-1.5 h-8 bg-yellow-500 rounded-full"></div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Write Your Review</h3>
                                </div>
                                <button wire:click="$set('showTestimonialForm', false)"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <form wire:submit.prevent="submitTestimonial" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Your Rating</label>
                                    <div class="flex items-center gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    wire:click="$set('testimonialRating', {{ $i }})"
                                                    class="w-10 h-10 transition-all {{ $testimonialRating >= $i ? 'text-yellow-400 scale-110' : 'text-gray-300 hover:text-gray-400' }}">
                                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    @error('testimonialRating')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Your Review</label>
                                    <textarea wire:model="testimonialContent"
                                              rows="4"
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#1A8DFF] focus:ring-0 transition-colors resize-none"
                                              placeholder="Share your experience at this event..."></textarea>
                                    @error('testimonialContent')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @session('testimonial_submitted')
                                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                                        <div class="flex items-center gap-2 text-green-700">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-medium">{{ session('testimonial_submitted') }}</span>
                                        </div>
                                    </div>
                                @endsession

                                <div class="flex items-center gap-3 pt-4">
                                    <button type="submit"
                                            class="px-8 py-3 bg-gradient-to-r from-[#1A8DFF] to-[#0066CC] text-white rounded-xl text-sm font-bold hover:shadow-lg hover:scale-105 transition-all">
                                        Submit Review
                                    </button>
                                    <button type="button"
                                            wire:click="$set('showTestimonialForm', false)"
                                            class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                    @endif

                    {{-- Recommendations Section --}}
                    @if($this->relatedEvents->isNotEmpty())
                    <section class="border-t border-gray-100 pt-16">
                        <div class="flex items-center justify-between mb-10">
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight">You Might Also Like</h2>
                            <button class="text-sm font-bold text-[#1A8DFF] hover:underline">See All Events</button>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                            @foreach($this->relatedEvents->take(5) as $rel)
                                <a href="{{ route('events.show', $rel->slug) }}" class="group block space-y-4">
                                    <div class="aspect-[16/10] rounded-[24px] overflow-hidden bg-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.05)] group-hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] transition-all duration-500">
                                        @if($rel->banner)
                                            <img src="{{ Str::startsWith($rel->banner->url, 'http') ? $rel->banner->url : Storage::url($rel->banner->file_path) }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700">
                                        @endif
                                    </div>
                                    <div class="space-y-2 px-1">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-blue-50 text-[#1A8DFF] text-[9px] font-black uppercase tracking-widest rounded-md">
                                                {{ $rel->event_date?->format('M j') }}
                                            </span>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $rel->location }}</p>
                                        </div>
                                        <h4 class="font-black text-gray-900 text-sm line-clamp-2 leading-snug group-hover:text-[#1A8DFF] transition-colors">{{ $rel->title }}</h4>
                                        <p class="text-sm font-black text-gray-900">IDR {{ number_format($rel->ticketTypes->min('price') ?? 0) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-footer />
</div>
