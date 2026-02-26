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
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden shadow-skeuo relative bg-black">
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
                                class="absolute left-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 glass-btn rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all group/btn">
                            <svg class="w-6 h-6 text-gray-700 group-hover/btn:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        
                        {{-- Mobile Top Navigation --}}
                        <div class="absolute top-4 left-4 z-30 md:hidden">
                            <button onclick="history.back()" class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:scale-105 active:scale-95 transition-all text-black shadow-lg">
                                <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                        </div>

                         {{-- Top Right Actions --}}
                         <div class="absolute top-4 right-4 z-30 flex gap-2">
                             {{-- Favorite Button --}}
                             <button wire:click="toggleFavorite"
                                     class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow-lg group/fav">
                                 @if($this->isFavorited)
                                     <svg class="w-5 h-5 text-[#dc2626] transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                         <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                     </svg>
                                 @else
                                     <svg class="w-5 h-5 text-gray-400 transition-colors group-hover/fav:text-[#dc2626]" fill="currentColor" viewBox="0 0 24 24">
                                         <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                     </svg>
                                 @endif
                             </button>

                             {{-- Share Button (Mobile Only) --}}
                             <button x-data="{
                                        shareUrl() {
                                            if (navigator.share) {
                                                navigator.share({
                                                    title: '{{ addslashes($event->title) }}',
                                                    url: '{{ route('events.show', $event->slug) }}'
                                                }).catch(console.error);
                                            }
                                        }
                                     }"
                                     @click="shareUrl()"
                                     class="w-10 h-10 bg-white rounded-full flex flex md:hidden items-center justify-center hover:scale-105 active:scale-95 transition-all text-gray-700 shadow-lg">
                                 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                     <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/>
                                 </svg>
                             </button>
                         </div>
                        
                        {{-- Next Button --}}
                        <button x-show="images.length > 1"
                                @click="next(); stopAutoSlide(); startAutoSlide();"
                                class="absolute right-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 glass-btn rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all group/btn">
                            <svg class="w-6 h-6 text-gray-700 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="flex-1 space-y-6">
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4">
                            <span class="px-3 py-1 bg-red-500/20 backdrop-blur-md rounded-full text-red-500 text-[10px] font-bold uppercase tracking-widest border border-red-500/30">
                                {{ __('event.recommended') }}
                            </span>
                            @if($event->category)
                            <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-white/80 text-[10px] font-bold uppercase tracking-widest border border-white/10">
                                {{ $event->category->name }}
                            </span>
                            @endif
                        </div>
                        <h1 class="text-2xl md:text-3xl lg:text-[40px] font-bold text-white leading-snug tracking-tight">
                            {{ $event->title }}
                        </h1>
                    </div>

                    <div class="space-y-4 pt-2">
                        {{-- Location --}}
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 text-gray-300 shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-200 text-sm leading-relaxed">
                                    {{ $event->venue_name ? $event->venue_name . ', ' : '' }}{{ $event->location }}
                                </p>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 text-gray-300 shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7v-5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-200 text-sm leading-relaxed">
                                    {{ $event->event_date?->format('l, d F Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Ticket Price --}}
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 text-gray-300 shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 10V6a2 2 0 00-2-2H4a2 2 0 00-2 2v4c1.1 0 2 .9 2 2s-.9 2-2 2v4a2 2 0 002 2h16a2 2 0 002-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-2-4v2.41a3.998 3.998 0 000 7.18V18H4v-2.41a3.998 3.998 0 000-7.18V6h16zM11 15h2v2h-2zm0-4h2v2h-2zm0-4h2v2h-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-200 text-sm leading-relaxed whitespace-nowrap">
                                    Ticket prices start from Rp {{ number_format($this->ticketTypes->min('price') ?? $event->ticket_prices ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Card --}}
                    <div class="mt-8 bg-[#1f1f1f] rounded-2xl p-4 md:p-5 border border-white/5 backdrop-blur-sm max-w-xl">
                        <p class="text-gray-300 text-[13px] mb-3">Available now, secure your spot!</p>
                        <button wire:click="book" class="w-full relative inline-flex items-center justify-center overflow-hidden rounded-xl p-0.5 font-bold transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] group">
                            <span class="absolute inset-0 bg-[#EE2E24]"></span>
                            <span class="relative w-full px-8 py-3 bg-[#EE2E24] rounded-xl text-white flex items-center justify-center transition-all group-hover:bg-opacity-90">
                                <span class="text-sm font-bold tracking-wide">Buy ticket now</span>
                            </span>
                        </button>
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
                        
                        <div class="flex-1 space-y-12 order-2 xl:order-1">
                            <section class="relative">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-1.5 h-8 bg-[#EF4444] rounded-full shadow-[0_0_10px_rgba(239,68,68,0.5)]"></div>
                                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('event.about_event') }}</h2>
                                </div>
                                
                                {{-- Description --}}
                                <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed prose-headings:text-gray-900 prose-headings:font-black prose-a:text-[#DC2626] prose-img:rounded-3xl prose-img:shadow-2xl">
                                    {!! app(\App\Services\HtmlSanitizerService::class)->sanitize($event->description) !!}
                                </div>

                                {{-- Event Rules --}}
                                @if($event->rules && $event->rules->isNotEmpty())
                                    <div class="mt-8 pt-8 border-t border-gray-100">
                                        <div class="flex items-center gap-3 mb-6">
                                            <div class="w-1.5 h-8 bg-green-500 rounded-full"></div>
                                            <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ __('event.rules_terms') }}</h3>
                                        </div>
                                        <div class="space-y-3">
                                            @foreach($event->rules as $rule)
                                                <div class="flex items-start gap-3 p-4 rounded-xl bg-green-50 border border-green-200">
                                                    <div class="w-6 h-6 rounded-lg bg-green-500 flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2. d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="font-bold text-gray-900 text-base mb-1">{{ $rule->name }}</h4>
                                                        @if($rule->value)
                                                            <p class="text-sm text-gray-600 leading-relaxed">{!! $rule->value !!}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </section>
                            
                            {{-- Location Section --}}
                            <section class="border-t border-gray-100 pt-12">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-1.5 h-8 bg-red-500 rounded-full"></div>
                                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('event.location_directions') }}</h2>
                                </div>

                                <div class="group bg-white border border-gray-100 rounded-[32px] overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.03)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] transition-all duration-500">
                                    <div class="h-80 w-full relative bg-gray-50 overflow-hidden shadow-skeuo border-2 border-white rounded-[32px]">
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
                                            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 border border-red-100 shrink-0">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('event.venue_address') }}</p>
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
                                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-200">
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
                                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#1877F2] hover:bg-gray-50 transition-all duration-200">
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
                        <div class="w-full lg:w-[420px] shrink-0 order-1 xl:order-2" id="tickets-section">
                            <div class="sticky top-12 space-y-6">
                                <div class="bg-white rounded-[32px] border border-gray-100 shadow-[0_30px_100px_rgba(0,0,0,0.08)] overflow-hidden">
                                    {{-- Available now bar integrated into sidebar --}}
                                    <div x-show="scrolled"
                                         x-transition:enter="transition ease-out duration-500"
                                         x-transition:enter-start="opacity-0 -translate-y-4"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="p-6 bg-red-50 border-b border-red-100 flex items-center justify-between gap-4">
                                        <div class="space-y-0.5">
                                            <p class="text-gray-900 font-bold text-sm">{{ __('event.tickets_selling_fast') }}</p>
                                            <p class="text-gray-500 text-[11px] leading-tight">{{ __('event.select_category') }}</p>
                                        </div>
                                        <button wire:click="book"
                                           class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs font-bold hover:scale-105 transition-all shadow-lg active:scale-95 bg-[#EE2E24] text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path>
                                            </svg>
                                            {{ __('event.buy_ticket_now') }}
                                        </button>
                                    </div>

                                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                                        <h3 class="font-black text-gray-900 uppercase tracking-[0.2em] text-[11px]">{{ __('event.ticket_categories') }}</h3>
                                    </div>
                                    
                                    <div class="p-8 space-y-4">
                                        @if($this->ticketTypes->isNotEmpty())
                                            @foreach($this->ticketTypes->sortBy('price') as $ticket)
                                                <div class="group relative p-5 rounded-2xl pill-card border-2 border-transparent transition-all duration-300">
                                                    <div class="flex justify-between items-start">
                                                        <div class="space-y-1">
                                                            <h4 class="font-bold text-gray-900 text-[15px] group-hover:text-red-600 transition-colors">{{ $ticket->name }}</h4>
                                                            <p class="text-[11px] text-gray-500 font-medium">{{ __('event.standard_admission') }}</p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-black text-gray-900 text-lg tracking-tight">Rp {{ number_format($ticket->price, 0, ',', '.') }}</p>
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
                                    </div>
                                </div>
                                                                
                                </div> {{-- closes sticky top-12 --}}
                            </div> {{-- closes lg:w-[420px] --}}
                        </div> {{-- closes Detail/Sidebar row --}}

                    {{-- Testimonials Section --}}
                    @if($this->approvedTestimonials->isNotEmpty())
                        @php
                            $testimonials = $this->approvedTestimonials;
                            $totalReviews = $testimonials->count();
                            $averageRating = $totalReviews > 0 ? $testimonials->avg('rating') : 0;
                            
                            $starCounts = [
                                5 => $testimonials->where('rating', 5)->count(),
                                4 => $testimonials->where('rating', 4)->count(),
                                3 => $testimonials->where('rating', 3)->count(),
                                2 => $testimonials->where('rating', 2)->count(),
                                1 => $testimonials->where('rating', 1)->count(),
                            ];
                        @endphp

                        <section class="border-t border-gray-100 pt-16" id="reviews" x-data="{ ratingFilter: 'all', sortBy: 'newest' }">
                            <div class="mb-12">
                                <div class="flex items-center gap-4 mb-2">
                                    <div class="w-1.5 h-8 bg-[#ff4747] rounded-full"></div>
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ __('event.what_people_say') }}</h2>
                                </div>
                                <p class="text-gray-500 font-medium ml-5.5">{{ __('event.real_feedback_attendees', ['count' => $totalReviews]) }}</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                                {{-- Left Column: Summary & Filters --}}
                                <div class="lg:col-span-4 space-y-8">
                                    {{-- Rating Summary Card --}}
                                    <div class="bg-white rounded-[24px] p-8 border border-gray-100 shadow-skeuo">
                                        <div class="flex items-end gap-4 mb-8">
                                            <span class="text-7xl font-black text-gray-900 tracking-tighter leading-none">{{ number_format($averageRating, 1) }}</span>
                                            <div class="pb-2 space-y-1">
                                                <div class="flex items-center gap-1">
                                                    @for($i=1; $i<=5; $i++)
                                                        <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-[#ff4747]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest pl-0.5">OUT OF 5</p>
                                            </div>
                                        </div>

                                        {{-- Progress Bars --}}
                                        <div class="space-y-3 mb-8">
                                            @foreach([5,4,3,2,1] as $star)
                                                @php 
                                                    $count = $starCounts[$star];
                                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                                @endphp
                                                <div class="flex items-center gap-4 text-sm group cursor-pointer" @click="ratingFilter = ratingFilter === {{ $star }} ? 'all' : {{ $star }}">
                                                    <span class="font-bold text-gray-900 w-3 text-right group-hover:text-[#ff4747] transition-colors">{{ $star }}</span>
                                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                        <div class="h-full bg-[#ff4747] rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                    <span class="font-medium text-gray-400 w-8 text-right group-hover:text-gray-900 transition-colors">{{ round($percentage) }}%</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Write Review CTA --}}
                                        @if($this->canSubmitTestimonial)
                                            <a href="{{ route('events.review', ['slug' => $event->slug]) }}" 
                                               class="flex w-full items-center justify-center gap-2 px-6 py-4 bg-[#ff4747] text-white rounded-xl font-bold hover:bg-[#ff3333] hover:shadow-[0_10px_20px_rgba(255,71,71,0.3)] hover:-translate-y-0.5 transition-all active:scale-95">
                                                Write a Review
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Filter Panel --}}
                                    <div class="bg-white rounded-[24px] p-6 border border-gray-100 shadow-skeuo">
                                        <h3 class="font-bold text-gray-900 mb-4">Filter Reviews</h3>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Sort By</label>
                                                <div class="relative">
                                                    <select x-model="sortBy" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#ff4747] focus:border-[#ff4747] block w-full p-2.5 font-bold cursor-pointer">
                                                        <option value="newest">Newest First</option>
                                                        <option value="oldest">Oldest First</option>
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Rating Range</label>
                                                <div class="flex flex-wrap gap-2">
                                                    <button @click="ratingFilter = 'all'" 
                                                            :class="ratingFilter === 'all' ? 'bg-[#ff4747] text-white border-[#ff4747]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'"
                                                            class="px-4 py-2 text-xs font-bold rounded-xl border transition-all">
                                                        All
                                                    </button>
                                                    @foreach([5,4,3] as $star)
                                                    <button @click="ratingFilter = {{ $star }}" 
                                                            :class="ratingFilter === {{ $star }} ? 'bg-[#ff4747] text-white border-[#ff4747]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'"
                                                            class="px-4 py-2 text-xs font-bold rounded-xl border transition-all">
                                                        {{ $star }} Stars
                                                    </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right Column: Reviews List --}}
                                <div class="lg:col-span-8 space-y-4" :class="{'flex flex-col-reverse': sortBy === 'oldest'}">
                                    @foreach($testimonials->take(3) as $testimonial)
                                        <div x-show="ratingFilter === 'all' || ratingFilter === {{ $testimonial->rating }}"
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 translate-y-4"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                              class="bg-white rounded-[24px] p-8 border border-gray-100 hover:border-gray-200 shadow-skeuo hover:shadow-float transition-all duration-300 relative group">
                                            
                                            @if($testimonial->is_featured)
                                                <div class="absolute -top-3 -right-3 z-10">
                                                    <span class="px-3 py-1 bg-[#ff4747] text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-red-500/30">
                                                        Featured Review
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="flex items-start gap-4 sm:gap-6">
                                                {{-- Avatar --}}
                                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-lg sm:text-xl shrink-0 shadow-lg shadow-red-500/20">
                                                    {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $testimonial->user->name }}</h4>
                                                                <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-widest rounded-md border border-green-100">Verified</span>
                                                            </div>
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <div class="flex gap-0.5">
                                                                    @for($i=1; $i<=5; $i++)
                                                                        <svg class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'text-[#ff4747]' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                        </svg>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="text-xs text-gray-400 font-medium whitespace-nowrap">
                                                            {{ $testimonial->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>

                                                    <div class="text-gray-600 leading-relaxed mb-6 text-sm sm:text-base">
                                                        "{{ $testimonial->content }}"
                                                    </div>

                                                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Was this helpful?</span>
                                                        <div class="flex items-center gap-4">
                                                            <button wire:click="voteOnTestimonial({{ $testimonial->id }}, true)"
                                                                    class="group flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-gray-700 transition-colors">
                                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                                </svg>
                                                                {{ $testimonial->helpful_votes_count }}
                                                            </button>
                                                            <button wire:click="voteOnTestimonial({{ $testimonial->id }}, false)"
                                                                    class="group flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-gray-700 transition-colors">
                                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform mt-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                                                                </svg>
                                                                {{ $testimonial->not_helpful_votes_count }}
                                                            </button>
                                                           
                                                            <button class="text-gray-300 hover:text-red-500 transition-colors ml-2">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($testimonials->count() > 3)
                                        <div class="text-center pt-8">
                                            <a href="{{ route('events.reviews', $event->slug) }}" 
                                               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-gray-900 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                                                Load More Reviews
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @elseif($this->canSubmitTestimonial)
                         {{-- Simple submission if no reviews yet --}}
                         <section class="border-t border-gray-100 pt-8 mb-8" x-data>
                             <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-[24px] p-8 border border-red-100">
                                 <div class="flex items-start justify-between gap-4">
                                     <div>
                                         <h3 class="font-bold text-gray-900 text-lg mb-1">Be the first to review!</h3>
                                         <p class="text-gray-600 text-sm">Attended this event? Share your experience.</p>
                                     </div>
                                     <a href="{{ route('events.review', ['slug' => $event->slug]) }}"
                                        class="shrink-0 px-6 py-3 glass-btn-primary text-white rounded-xl text-sm font-bold hover:shadow-lg hover:scale-105 transition-all">
                                         Write a Review
                                     </a>
                                 </div>
                             </div>
                         </section>
                    @endif
 
                    {{-- Recommendations Section --}}
                    @if($this->relatedEvents->isNotEmpty())
                    <section class="border-t border-gray-100 pt-16">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">You Might Also Like This</h2>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                            @foreach($this->relatedEvents->take(4) as $rel)
                                <a href="{{ route('events.show', $rel->slug) }}" class="group flex flex-col bg-white rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] transition-all duration-300 overflow-hidden h-full border border-gray-100 hover:-translate-y-1">
                                    <div class="aspect-[4/3] w-full overflow-hidden bg-gray-100 shrink-0 relative">
                                        @if($rel->banner)
                                            <img src="{{ Str::startsWith($rel->banner->url, 'http') ? $rel->banner->url : Storage::url($rel->banner->file_path) }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        @endif
                                    </div>
                                    <div class="p-4 md:p-5 flex flex-col flex-1">
                                        <p class="text-[13px] md:text-sm text-gray-500 mb-1.5">
                                            {{ $rel->event_date?->format('d M Y') }}
                                        </p>
                                        <h4 class="font-bold text-gray-900 text-sm md:text-base line-clamp-2 leading-snug mb-1 group-hover:text-red-500 transition-colors uppercase">{{ $rel->title }}</h4>
                                        <p class="text-[13px] md:text-sm text-gray-500 mb-4">{{ $rel->location }}</p>
                                        
                                        <div class="mt-auto">
                                            <p class="text-sm md:text-base font-bold text-[#EE2E24]">Rp {{ number_format($rel->ticketTypes->min('price') ?? 0, 0, ',', '.') }}</p>
                                        </div>
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

    {{-- Mobile Sticky Buy Button --}}
    <div x-cloak
         x-show="scrolled"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-full"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-full"
         class="fixed bottom-0 left-0 right-0 z-[100] p-4 bg-white/90 backdrop-blur-md border-t border-gray-100 shadow-[0_-10px_30px_rgba(0,0,0,0.1)] xl:hidden pb-safe">
        <div class="flex items-center justify-between gap-4">
            <div class="space-y-0.5">
                <p class="text-gray-500 text-[11px] font-bold uppercase tracking-wider">Starting from</p>
                <p class="text-gray-900 font-black text-lg leading-none">Rp {{ number_format($this->ticketTypes->min('price') ?? $event->ticket_prices ?? 0, 0, ',', '.') }}</p>
            </div>
            <button wire:click="book"
                    class="flex items-center justify-center gap-2 px-8 py-3.5 bg-[#EE2E24] text-white rounded-xl text-sm font-bold shadow-[0_8px_16px_rgba(238,46,36,0.3)] hover:bg-[#d9261d] active:scale-95 transition-all">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path>
                </svg>
                {{ __('event.buy_ticket_now') }}
            </button>
        </div>
    </div>

    <x-footer />
</div>
