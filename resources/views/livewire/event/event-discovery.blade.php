@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;

    $heroSlides = (isset($heroBanners) && $heroBanners->isNotEmpty())
        ? $heroBanners->map(fn ($b) => [
            'id'          => $b->id,
            'image'       => $b->fileBucket?->url
                ?? (str_starts_with($b->image_path ?? '', 'http') ? $b->image_path : Storage::url($b->image_path ?? '')),
            'title'       => $b->title,
            'description' => '',
            'link'        => $b->link_url ?? route('events.index'),
            'link_target' => $b->link_target ?? '_self',
        ])->values()->toArray()
        : [
            ['id' => 0, 'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070&auto=format&fit=crop', 'title' => 'Music Festival 2026',      'description' => 'Three days of non-stop entertainment, world-class artists, and unforgettable memories. Get your tickets before they sell out!', 'link' => route('events.index'), 'link_target' => '_self'],
            ['id' => 0, 'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2074&auto=format&fit=crop', 'title' => 'Summer Rock Concert',     'description' => 'Experience the biggest rock bands live in an electrifying outdoor festival. Limited tickets available!',                                 'link' => route('events.index'), 'link_target' => '_self'],
            ['id' => 0, 'image' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=2070&auto=format&fit=crop', 'title' => 'Electronic Dance Night', 'description' => 'Dance the night away with top DJs spinning the hottest tracks. VIP packages now on sale!',                                        'link' => route('events.index'), 'link_target' => '_self'],
        ];
@endphp

<div class="bg-[#eef2f6] min-h-screen font-sans text-gray-800 selection:bg-red-500 selection:text-white">
    {{-- 
        SKEUOMORPHISM 2.0: Ambient Light Source 
        We use a global gradient overlay to simulate a soft light source from the top-left.
    --}}
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-br from-white/40 via-transparent to-gray-200/30 z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-10 space-y-16">
        
        {{-- 
            HERO CAROUSEL - Clean & Modern Design
            Matching reference image: Full-width background, simple overlay, clean typography
        --}}
        <script>
            document.addEventListener('alpine:init', function () {
                if (typeof Alpine !== 'undefined' && !Alpine.stores?.heroCarouselRegistered) {
                    Alpine.data('heroCarousel', function () {
                        return {
                            activeSlide: 0,
                            slides: @json($heroSlides),
                            trackImpression: function () {
                                var slide = this.slides[this.activeSlide];
                                if (slide && slide.id > 0) {
                                    window.dispatchEvent(new CustomEvent('banner-impression', { detail: { id: slide.id } }));
                                }
                            },
                            next: function () {
                                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                                this.trackImpression();
                            },
                            prev: function () {
                                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                                this.trackImpression();
                            },
                            init: function () {
                                var self = this;
                                this.trackImpression();
                                setInterval(function () { self.next(); }, 5000);
                            }
                        };
                    });
                }
                
                if (typeof Alpine !== 'undefined' && !Alpine.stores?.searchManagerRegistered) {
                    Alpine.data('searchManager', () => ({
                        recentSearches: [],
                        init() {
                            const saved = localStorage.getItem('recent_searches');
                            this.recentSearches = saved ? JSON.parse(saved) : [];
                        },
                        saveSearch(query) {
                            if (!query || query.trim().length < 2) return;
                            query = query.trim();
                            
                            // Prevent saving if the query is too similar to the last one (simple check)
                            if (this.recentSearches.length > 0 && this.recentSearches[0].toLowerCase() === query.toLowerCase()) return;

                            this.recentSearches = [
                                query,
                                ...this.recentSearches.filter(s => s.toLowerCase() !== query.toLowerCase())
                            ].slice(0, 5);
                            localStorage.setItem('recent_searches', JSON.stringify(this.recentSearches));
                        },
                        performSearch(query) {
                            this.$wire.set('search', query);
                            this.saveSearch(query);
                        },
                        clearSearches() {
                            this.recentSearches = [];
                            localStorage.removeItem('recent_searches');
                        }
                    }));
                }
            });

            // Also register immediately in case alpine:init already fired
            if (typeof Alpine !== 'undefined') {
                try {
                    Alpine.data('heroCarousel', function () {
                        return {
                            activeSlide: 0,
                            slides: @json($heroSlides),
                            trackImpression: function () {
                                var slide = this.slides[this.activeSlide];
                                if (slide && slide.id > 0) {
                                    window.dispatchEvent(new CustomEvent('banner-impression', { detail: { id: slide.id } }));
                                }
                            },
                            next: function () {
                                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                                this.trackImpression();
                            },
                            prev: function () {
                                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                                this.trackImpression();
                            },
                            init: function () {
                                var self = this;
                                this.trackImpression();
                                setInterval(function () { self.next(); }, 5000);
                            }
                        };
                    });

                    Alpine.data('searchManager', () => ({
                        recentSearches: [],
                        init() {
                            const saved = localStorage.getItem('recent_searches');
                            this.recentSearches = saved ? JSON.parse(saved) : ['Rock Concerts', 'Jazz Night', 'Food Festival NYC', 'Stand-up Comedy'];
                        },
                        saveSearch(query) {
                            if (!query || query.trim().length < 2) return;
                            query = query.trim();
                            
                            if (this.recentSearches.length > 0 && this.recentSearches[0].toLowerCase() === query.toLowerCase()) return;

                            this.recentSearches = [
                                query,
                                ...this.recentSearches.filter(s => s.toLowerCase() !== query.toLowerCase())
                            ].slice(0, 5);
                            localStorage.setItem('recent_searches', JSON.stringify(this.recentSearches));
                        },
                        performSearch(query) {
                            this.$wire.set('search', query);
                            this.saveSearch(query);
                        },
                        clearSearches() {
                            this.recentSearches = [];
                            localStorage.removeItem('recent_searches');
                        }
                    }));
                } catch (e) { /* already registered */ }
            }
        </script>
        <div x-data="heroCarousel()"
            @banner-impression.window="$wire.call('trackBannerImpression', $event.detail.id)"
            class="relative w-full h-[500px] rounded-3xl overflow-hidden"
        >
            <!-- Slides -->
            <template x-for="(slide, index) in slides" :key="slide.id">
                <div 
                    x-show="activeSlide === index"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0"
                >
                    <!-- Background Image -->
                    <img :src="slide.image" 
                         class="absolute inset-0 w-full h-full object-cover"
                         :alt="slide.title">
                    
                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>
                    
                    <!-- Content -->
                    <div class="relative z-10 h-full flex flex-col justify-center px-6 md:px-16 lg:px-20 max-w-3xl">
                        <!-- Featured Badge -->
                        <div class="mb-6">
                            <span class="inline-block bg-[#EE2E24] text-white text-xs font-bold uppercase tracking-wider px-4 py-2 rounded">
                                FEATURED EVENT
                            </span>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="text-white text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4 md:mb-6 leading-tight" x-text="slide.title"></h1>
                        
                        <!-- Description -->
                        <p class="text-white text-sm md:text-base lg:text-lg mb-6 md:mb-8 leading-relaxed max-w-xl md:max-w-2xl line-clamp-3 md:line-clamp-none" x-text="slide.description"></p>
                        
                        <!-- Buttons -->
                        <div class="flex flex-wrap gap-4">
                            <a :href="slide.link"
                               :target="slide.link_target"
                               @click="slide.id > 0 && $wire.call('trackBannerClick', slide.id)"
                               class="group inline-flex items-center gap-2 bg-[#EE2E24] hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:scale-105 hover:shadow-[0_8px_20px_rgba(238,46,36,0.4)] active:scale-95">
                                Discover Events
                                <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Slider Controls (Dots + Arrows) -->
            <div class="absolute bottom-4 right-4 md:bottom-6 md:right-8 z-30 flex items-center gap-4 md:gap-6">
                <!-- Dots -->
                <div class="hidden sm:flex gap-2">
                    <template x-for="(slide, index) in slides" :key="'dot-' + slide.id">
                        <button 
                            @click="activeSlide = index"
                            class="w-2 h-2 rounded-full transition-all duration-300"
                            :class="activeSlide === index ? 'bg-[#EE2E24] w-8' : 'bg-white/50 hover:bg-white'"
                        ></button>
                    </template>
                </div>

                <!-- Arrows -->
                <div class="flex items-center gap-2 sm:border-l sm:border-white/20 sm:pl-6">
                    <button 
                        @click="prev()" 
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 flex items-center justify-center text-white transition-all active:scale-95"
                        aria-label="Previous slide"
                    >
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button 
                        @click="next()" 
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 flex items-center justify-center text-white transition-all active:scale-95"
                        aria-label="Next slide"
                    >
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>


        </div>

        {{-- 
            RECENTLY SEARCHED: Clean White Pills
            Matching the user's reference image exactly: "RECENTLY SEARCHED" label, white pills with red icons.
        --}}
        <div x-data="searchManager()" class="px-2 max-w-screen-xl mx-auto space-y-8">
            <!-- Search Bar (Part of Search Manager) -->
            <div class="relative !-mt-28 z-30 max-w-5xl mx-auto px-2">
                <div class="bg-white rounded-xl shadow-xl p-2 flex flex-col md:flex-row items-stretch md:items-center gap-2">
                    <!-- Search Input -->
                    <div class="flex-1 flex items-center gap-3 bg-gray-50/80 rounded-lg px-4 py-3 w-full">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input 
                            wire:model.live.debounce.300ms="search"
                            @input.debounce.2000ms="saveSearch($event.target.value)"
                            @keydown.enter="saveSearch($event.target.value)"
                            class="flex-1 text-sm text-gray-700 placeholder-gray-400 bg-transparent border-none focus:outline-none focus:ring-0 p-0" 
                            placeholder="Search events, concerts, and more..." 
                            type="text"
                        />
                    </div>

                    <div class="flex flex-row items-stretch gap-2 w-full md:w-auto">
                        <!-- Location Dropdown -->
                        <button 
                            wire:click="toggleLocationModal"
                            class="flex-1 flex items-center justify-between gap-2 bg-gray-50/80 rounded-lg px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-medium whitespace-nowrap truncate">{{ $selectedLocation ?? 'Everywhere' }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
        
                        <!-- Filter Button -->
                        <button 
                            wire:click="toggleFilterModal"
                            class="bg-[#EE2E24] hover:bg-red-600 text-white font-bold px-6 py-3 rounded-lg flex items-center gap-2 transition-all duration-300 hover:scale-105 hover:-translate-y-0.5 hover:shadow-[0_8px_15px_rgba(238,46,36,0.3)] active:scale-95 whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4" x-show="recentSearches.length > 0">
                <div class="flex items-center justify-between mb-4 ml-1">
                    <h2 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Recently Searched</h2>
                    <button @click="clearSearches()" class="text-[10px] font-bold text-red-500 uppercase tracking-widest hover:text-red-600 transition-colors flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Clear
                    </button>
                </div>
                <div class="flex flex-wrap gap-3">
                    <template x-for="search in recentSearches" :key="search">
                        <button 
                            @click="performSearch(search)"
                            class="group px-6 py-3 rounded-full bg-white text-[13px] font-bold text-gray-700 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all outline-none border border-transparent flex items-center gap-2.5"
                        >
                            <svg class="w-4 h-4 text-[#EE2E24]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span x-text="search"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- 
            HOT DEALS SECTION
            Two distinctive cards: Early Bird (Red) & VIP Access (Dark).
        --}}
        <div class="space-y-6 mt-12 mb-12" x-data="{ slider: null }" x-init="slider = $refs.hotDealsSlider">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">Hot Deals</h2>
                <div class="flex gap-2">
                    <button
                        type="button"
                        class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors"
                        x-on:click="slider && slider.scrollBy({ left: -(slider.clientWidth * 0.9), behavior: 'smooth' })"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button
                        type="button"
                        class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors"
                        x-on:click="slider && slider.scrollBy({ left: slider.clientWidth * 0.9, behavior: 'smooth' })"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>

            <div
                x-ref="hotDealsSlider"
                class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scroll-smooth scrollbar-hide"
            >
                <!-- Card 1: Early Bird Special (Red Gradient) -->
                <div class="relative w-full shrink-0 md:w-[48%] snap-start overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#e64747] to-[#e66e47] p-8 md:p-10 text-white shadow-lg shadow-red-500/20 group">
                    <!-- Ticket Icon Watermark -->
                    <div class="absolute top-8 right-8 text-white/10 rotate-12 transform group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>

                    <span class="inline-block px-3 py-1 rounded bg-white/20 text-[10px] font-bold uppercase tracking-wider mb-4 border border-white/20">Limited Time Offer</span>
                    
                    <h3 class="text-3xl md:text-4xl font-black italic tracking-tighter mb-2">EARLY BIRD <br> SPECIAL</h3>
                    <p class="text-red-100 font-medium text-sm mb-8 opacity-90">Get 20% off on all music festivals this summer.</p>

                    <div class="flex items-end justify-between">
                        <!-- Countdown -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center p-2 rounded-lg bg-black/20 backdrop-blur-sm min-w-[3.5rem]">
                                <span class="text-lg font-bold leading-none">02</span>
                                <span class="text-[9px] uppercase opacity-75 mt-1">Days</span>
                            </div>
                            <div class="flex flex-col items-center p-2 rounded-lg bg-black/20 backdrop-blur-sm min-w-[3.5rem]">
                                <span class="text-lg font-bold leading-none">14</span>
                                <span class="text-[9px] uppercase opacity-75 mt-1">Hrs</span>
                            </div>
                            <div class="flex flex-col items-center p-2 rounded-lg bg-black/20 backdrop-blur-sm min-w-[3.5rem]">
                                <span class="text-lg font-bold leading-none">45</span>
                                <span class="text-[9px] uppercase opacity-75 mt-1">Min</span>
                            </div>
                        </div>
                        
                        <button class="bg-white text-red-500 px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-gray-50 active:scale-95 transition-all duration-300 hover:scale-105 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/10">
                            Claim Now
                        </button>
                    </div>
                </div>

                <!-- Card 2: VIP Access (Dark) -->
                <div class="relative w-full shrink-0 md:w-[48%] snap-start overflow-hidden rounded-[2.5rem] bg-[#1e2330] p-8 md:p-10 text-white shadow-lg shadow-gray-900/10 group">
                    <span class="inline-block px-3 py-1 rounded bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider mb-4 border border-red-500/20">Flash Sale</span>
                    
                    <h3 class="text-3xl md:text-4xl font-black tracking-tighter mb-2">VIP ACCESS <br> UPGRADE</h3>
                    <p class="text-gray-400 font-medium text-sm mb-8">Upgrade to VIP for the price of regular tickets.</p>

                    <div class="flex items-end justify-between">
                        <!-- Countdown -->
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center p-2 rounded-lg bg-white/5 backdrop-blur-sm min-w-[3.5rem]">
                                <span class="text-lg font-bold leading-none text-red-500">00</span>
                                <span class="text-[9px] uppercase text-gray-500 mt-1">Days</span>
                            </div>
                            <div class="flex flex-col items-center p-2 rounded-lg bg-white/5 backdrop-blur-sm min-w-[3.5rem]">
                                <span class="text-lg font-bold leading-none text-red-500">05</span>
                                <span class="text-[9px] uppercase text-gray-500 mt-1">Hrs</span>
                            </div>
                            <div class="flex flex-col items-center p-2 rounded-lg bg-white/5 backdrop-blur-sm min-w-[3.5rem]">
                                <span class="text-lg font-bold leading-none text-red-500">30</span>
                                <span class="text-[9px] uppercase text-gray-500 mt-1">Min</span>
                            </div>
                        </div>
                        
                        <button class="bg-gradient-to-r from-[#EE2E24] to-red-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-red-900/30 hover:brightness-110 active:scale-95 transition-all duration-300 hover:scale-105 hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(238,46,36,0.4)]">
                            Upgrade
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 
            FILTER PILLS: Tactile Buttons 
            Raised state vs Pressed state logic.
        --}}
        <div class="flex items-center gap-4 overflow-x-auto pb-6 pt-2 scrollbar-hide px-2">
             <button 
                wire:click="resetFilters"
                @class([
                    'px-8 py-3 rounded-full text-sm font-bold tracking-wide whitespace-nowrap transition-all duration-200',
                    'bg-white text-gray-600 shadow-[0_4px_10px_rgba(0,0,0,0.05)] hover:shadow-md border border-gray-100' => !empty($selectedCategories) || $selectedLocation || $dateFilter !== 'all',
                    'bg-red-500 text-white shadow-lg shadow-red-500/30' => empty($selectedCategories) && !$selectedLocation && $dateFilter === 'all',
                ])
            >
                All Events
            </button>
            
            @foreach($categories->take(8) as $category)
                @php $isSelected = in_array($category->id, $selectedCategories); @endphp
                <button 
                    wire:click="toggleCategory('{{ $category->id }}')"
                    @class([
                        'px-8 py-3 rounded-full text-sm font-bold tracking-wide whitespace-nowrap transition-all duration-200',
                        'bg-white text-gray-600 shadow-[0_4px_10px_rgba(0,0,0,0.05)] hover:shadow-md border border-gray-100' => !$isSelected,
                        'bg-red-500 text-white shadow-lg shadow-red-500/30' => $isSelected,
                    ])
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <div class="space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between px-2">
                <h4 class="text-2xl font-black text-gray-900 tracking-tight">Upcoming Events</h2>
                <a href="{{ route('events.index') }}" class="text-red-500 font-bold hover:text-red-600 transition flex items-center gap-1 text-sm">
                    View all <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Grid/Slider -->
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-6 pt-4 -mx-4 px-4 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-6 sm:pb-0 sm:pt-0 sm:overflow-visible sm:mx-0 sm:px-0 scrollbar-hide items-stretch">
                @forelse($events as $event)
                    <div class="min-w-[85%] sm:min-w-0 snap-center group relative bg-white rounded-2xl p-3 shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_40px_-5px_rgba(0,0,0,0.1)] transition-all duration-300 hover:-translate-y-2 cursor-pointer border border-gray-100 flex flex-col justify-between h-full">
                        
                        <!-- Image Container -->
                        <div class="relative h-48 w-full rounded-xl overflow-hidden mb-5 shrink-0">
                            @if($event->banner)
                                <img src="{{ Storage::url($event->banner->file_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1.5 rounded-full bg-white/30 backdrop-blur-md text-[10px] font-bold text-white uppercase tracking-wider border border-white/20 shadow-sm">
                                    {{ $event->categories->first()?->name ?? 'Event' }}
                                </span>
                            </div>

                            <!-- Like Button -->
                            <div class="absolute top-4 right-4">
                                <button class="w-8 h-8 rounded-full bg-white/30 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all border border-white/20 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="px-2 pb-2 flex flex-col flex-1">
                            <!-- Date -->
                            <div class="flex items-center gap-2 text-red-500 font-bold text-[10px] uppercase tracking-wider mb-3">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $event->event_date->format('D, j F • h:i A') }}
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-black text-gray-900 leading-tight mb-3 line-clamp-2 h-14 overflow-hidden">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <!-- Location -->
                            <div class="flex items-center gap-2 mb-6 h-5 overflow-hidden">
                                <div class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shrink-0">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-400 truncate">{{ $event->location }}</span>
                            </div>

                            <!-- Ticket Notch Divider -->
                            <div class="mt-auto relative pt-4 border-t border-dashed border-gray-200">
                                <!-- Notches -->
                                <div class="absolute -left-[1.35rem] -top-3 w-6 h-6 rounded-full bg-[#eef2f6]"></div>
                                <div class="absolute -right-[1.35rem] -top-3 w-6 h-6 rounded-full bg-[#eef2f6]"></div>
                                
                                <!-- Price & Action -->
                                <div class="flex items-center justify-between mt-1">
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Starting from</p>
                                        @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                        <p class="text-xl font-black text-gray-900">
                                            {{ $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'FREE' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('events.show', $event->slug) }}" class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white hover:scale-110 hover:-rotate-12 transition-all duration-300 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                        <div class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-2">No events found</h3>
                        <p class="text-gray-500 mb-8 max-w-xs text-sm">We couldn't find matches for your search. Try adjusting filters.</p>
                        <button wire:click="resetFilters" class="px-8 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg shadow-red-500/30 hover:bg-red-600 transition-all">Clear Filters</button>
                    </div>
                @endforelse
            </div>
            
            @if($events->count() > 0)
                <div class="flex justify-center pt-10">
                     <a href="{{ route('events.index') }}" class="glass-btn-primary bg-[#EE2E24] text-white hover:bg-red-700 shadow-lg shadow-red-500/30 inline-flex items-center gap-3 px-10 py-4 rounded-full font-bold transition-all duration-300 hover:-translate-y-1">
                        View More Events
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>
            @endif
        </div>

        {{-- Section Banners (type = 'section') --}}
        @if(isset($sectionBanners) && $sectionBanners->isNotEmpty())
            @php
                $cols = match(true) {
                    $sectionBanners->count() === 1 => 'grid-cols-1',
                    $sectionBanners->count() === 2 => 'grid-cols-1 md:grid-cols-2',
                    default                        => 'grid-cols-1 md:grid-cols-3',
                };
            @endphp
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4" x-data="{}">
                <div class="grid {{ $cols }} gap-6">
                @foreach($sectionBanners as $banner)
                    @php
                        $sectionImgUrl = $banner->fileBucket?->url
                            ?? (str_starts_with($banner->image_path ?? '', 'http')
                                ? $banner->image_path
                                : Storage::url($banner->image_path ?? ''));
                    @endphp
                    <a href="{{ $banner->link_url ?? '#' }}"
                       target="{{ $banner->link_target }}"
                       x-on:click="$wire.call('trackBannerClick', {{ $banner->id }})"
                       class="relative block overflow-hidden rounded-3xl h-52 group cursor-pointer shadow-md hover:shadow-xl transition-shadow duration-300">
                        <img src="{{ $sectionImgUrl }}"
                             alt="{{ $banner->title }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6">
                            <h3 class="text-white text-xl font-black leading-tight drop-shadow">{{ $banner->title }}</h3>
                            @if($banner->link_url)
                                <span class="inline-flex items-center gap-1 mt-2 text-white/80 text-xs font-semibold">
                                    Learn more
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Trending Now Section -->
    <div class="w-full py-20 relative bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-1.5 h-8 bg-[#EE2E24] rounded-full"></div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Trending Now</h2>
            </div>
            
            @php
                $trendingEvents = collect($bestSellerEvents ?? [])->take(3);
                $mobileEvents = collect($bestSellerEvents ?? []);
            @endphp
            
            @if($mobileEvents->isEmpty())
                <div class="w-full h-[350px] rounded-[2rem] bg-gray-50 flex items-center justify-center border border-gray-100">
                    <p class="text-gray-500 font-medium">{{ __('No trending events yet') }}</p>
                </div>
            @else
                <!-- Desktop Grid Layout (hidden on mobile) -->
                <div class="hidden md:grid grid-cols-3 grid-rows-2 gap-6 h-[500px]">
                    @foreach($trendingEvents as $index => $event)
                        @php
                            $bannerUrl = $event->banner?->url ?? ($event->banners->first()?->url ?? 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070&auto=format&fit=crop');
                            $categoryName = $event->categories?->first()?->name ?? __('Event');
                        @endphp
                        
                        @if($index === 0)
                            <!-- Large Left Card -->
                            <div class="col-span-2 row-span-2 relative rounded-[1.5rem] overflow-hidden group cursor-pointer border border-black/5 shadow-sm flex flex-col justify-end" style="background: linear-gradient(0deg, rgba(17,24,39,0.95) 0%, rgba(17,24,39,0.2) 60%, transparent 100%), url('{{ $bannerUrl }}') center/cover no-repeat;">
                                <div class="absolute top-6 right-6 bg-[#EE2E24] text-white text-[10px] font-black uppercase px-3 py-1.5 rounded-lg shadow-md flex items-center gap-1">
                                    {{ __('Selling Fast') }} 🔥
                                </div>
                                <div class="relative z-10 p-10 max-w-2xl">
                                    <p class="text-[#EE2E24] font-bold mb-2 uppercase tracking-widest text-xs drop-shadow-sm">{{ $categoryName }}</p>
                                    <h3 class="text-3xl lg:text-4xl font-extrabold text-white mb-3">{{ $event->title }}</h3>
                                    <p class="text-gray-300 text-sm lg:text-base mb-6 line-clamp-2 leading-relaxed">{{ $event->short_description ?? Str::limit(strip_tags($event->description), 100) }}</p>
                                    <a href="{{ route('events.show', $event->slug) }}" class="group/btn inline-flex items-center gap-2 bg-[#EE2E24] text-white font-bold px-6 py-3 rounded-xl transition-all duration-300 hover:scale-105 hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(238,46,36,0.4)] active:scale-95 text-sm w-fit z-30 relative">
                                        {{ __('Get Tickets') }} 
                                        <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                                <a href="{{ route('events.show', $event->slug) }}" class="absolute inset-0 z-20"><span class="sr-only">View Event</span></a>
                            </div>
                        @else
                            <!-- Small Right Cards -->
                            <div class="col-span-1 row-span-1 relative rounded-[1.5rem] overflow-hidden group cursor-pointer border border-black/5 shadow-sm flex flex-col justify-end" style="background: linear-gradient(0deg, rgba(17,24,39,0.95) 0%, rgba(17,24,39,0.2) 70%, transparent 100%), url('{{ $bannerUrl }}') center/cover no-repeat;">
                                @if($index === 2)
                                    <div class="absolute top-5 right-5 bg-[#EE2E24] text-white text-[9px] font-black uppercase px-2.5 py-1.5 rounded-lg shadow-md">
                                        {{ __('Last Few Seats') }}
                                    </div>
                                @endif
                                <div class="relative z-10 p-6 lg:p-8">
                                    <p class="text-[#EE2E24] font-bold mb-1.5 uppercase tracking-widest text-[10px] drop-shadow-sm">{{ $categoryName }}</p>
                                    <h3 class="text-xl lg:text-2xl font-bold text-white leading-tight">{{ $event->title }}</h3>
                                </div>
                                <a href="{{ route('events.show', $event->slug) }}" class="absolute inset-0 z-20"><span class="sr-only">View Event</span></a>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Mobile Slider Layout (hidden on desktop) -->
                <div class="flex md:hidden overflow-x-auto snap-x snap-mandatory gap-4 pb-8 -mx-4 px-4 scrollbar-hide items-stretch scroll-smooth">
                    @foreach($mobileEvents as $event)
                        @php
                            $bannerUrl = $event->banner?->url ?? ($event->banners->first()?->url ?? 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070&auto=format&fit=crop');
                            $categoryName = $event->categories?->first()?->name ?? __('Event');
                        @endphp
                        <div class="min-w-[85%] shrink-0 snap-center relative h-[350px] rounded-[2rem] overflow-hidden group border border-gray-100 shadow-xl flex flex-col justify-end" style="background: linear-gradient(0deg, rgba(17,24,39,0.85) 20%, transparent 100%), url('{{ $bannerUrl }}') center/cover no-repeat;">
                            <div class="absolute top-5 right-5 bg-[#EE2E24] text-white text-[10px] font-black uppercase px-3 py-1.5 rounded-lg shadow-md">
                                {{ __('Selling Fast') }} 🔥
                            </div>
                            <div class="absolute bottom-0 left-0 p-6 w-full">
                                <div class="text-[#EE2E24] font-bold mb-2 uppercase tracking-widest text-xs drop-shadow-sm">{{ $categoryName }}</div>
                                <h3 class="text-2xl font-extrabold text-white mb-3 leading-tight">{{ $event->title }}</h3>
                                <a href="{{ route('events.show', $event->slug) }}" class="group bg-[#EE2E24] text-white border border-white/20 inline-flex items-center px-6 py-3 rounded-xl font-bold transition-all duration-300 hover:scale-105 hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(238,46,36,0.4)] active:scale-95 text-sm relative z-30 w-fit mt-1">
                                    {{ __('Get Tickets') }} <svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

        <!-- Testimonials Section -->
        <div class="relative z-10 py-24 bg-gradient-to-br from-[#EE2E24] to-[#c1271d] border-y border-white/10 shadow-[inset_0_2px_20px_rgba(0,0,0,0.1)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ slider: null }" x-init="slider = $refs.testimonialsSlider">
                <div class="flex flex-col items-center justify-center mb-16 w-full relative">
                    <div class="text-center max-w-2xl px-4 md:px-12">
                        <span class="text-red-100 font-bold uppercase tracking-[0.2em] text-[11px] mb-3 block">Community Feedback</span>
                        <h2 class="text-3xl md:text-4xl font-black text-white mb-4">What Fans Are Saying</h2>
                        <p class="text-white/80 md:text-lg leading-relaxed">Join thousands of happy attendees who have transformed their event experiences with us.</p>
                    </div>
                    <!-- Controls -->
                    <div class="hidden md:flex gap-2 absolute right-0 top-1/2 -translate-y-1/2">
                        <button type="button" class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md shadow-lg hover:bg-white/20 flex items-center justify-center text-white transition-all active:scale-95 border border-white/20" x-on:click="slider && slider.scrollBy({ left: -(slider.clientWidth * 0.9), behavior: 'smooth' })">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button type="button" class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md shadow-lg hover:bg-white/20 flex items-center justify-center text-white transition-all active:scale-95 border border-white/20" x-on:click="slider && slider.scrollBy({ left: slider.clientWidth * 0.9, behavior: 'smooth' })">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
                
            <div x-ref="testimonialsSlider" class="flex overflow-x-auto snap-x snap-mandatory gap-4 pt-6 pb-8 -mx-4 px-4 md:gap-8 md:mx-0 md:px-0 scrollbar-hide items-stretch scroll-smooth">
                @forelse($testimonials as $testimonial)
                    <div class="min-w-[85%] md:min-w-[350px] max-w-[350px] snap-center shrink-0 relative group">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-white shadow-md border-2 border-[#EE2E24] z-20"></div>
                        <div class="h-full min-h-[250px] md:min-h-[280px] bg-gradient-to-b from-[#fffcf5] to-white p-6 md:p-8 rounded-2xl shadow-[0_4px_6px_rgba(0,0,0,0.05),0_10px_15px_rgba(0,0,0,0.1)] border border-black/5 transform rotate-2 hover:rotate-0 transition-transform duration-300 flex flex-col">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-0.5 md:gap-1 text-yellow-400">
                                    @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                                        <svg class="w-3 h-3 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                @if($testimonial->event)
                                    <span class="text-[9px] font-bold text-red-500 uppercase tracking-tight truncate max-w-[150px]">{{ $testimonial->event->title }}</span>
                                @endif
                            </div>
                            <p class="text-gray-600 italic mb-6 leading-relaxed text-xs md:text-sm font-serif line-clamp-4">{{ $testimonial->content ?? __('No testimonial content') }}</p>
                            
                            <div class="flex items-center gap-2 md:gap-3 mt-auto pt-4 border-t border-gray-50">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-red-100 border-2 border-white shadow-md overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($testimonial->user?->avatar)
                                        <img src="{{ Storage::url($testimonial->user->avatar) }}" alt="{{ $testimonial->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        @php
                                            $initials = collect(explode(' ', $testimonial->user?->name ?? 'User'))
                                                ->map(fn($n) => mb_substr($n, 0, 1))
                                                ->take(2)
                                                ->join('');
                                        @endphp
                                        <span class="text-red-500 font-bold text-xs md:text-sm uppercase">{{ $initials }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-800 text-xs md:text-sm truncate">{{ $testimonial->user?->name ?? __('Anonymous') }}</p>
                                    <p class="text-[8px] md:text-[10px] text-gray-400 font-bold uppercase truncate">{{ $testimonial->location ?? __('Verified Attendee') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="min-w-[85%] md:min-w-[350px] max-w-[350px] snap-center shrink-0 relative group">
                        <div class="h-full bg-white/10 backdrop-blur-md p-4 md:p-8 rounded-2xl shadow border border-white/20 flex items-center justify-center">
                            <span class="text-white/60 text-sm font-medium">{{ __('No testimonials found.') }}</span>
                        </div>
                    </div>
                @endforelse
            </div>
            </div>
        </div>


        <!-- Organizers Section (Dynamic) -->
        <div class="relative z-10 py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-700 mb-12 text-center opacity-80">Trusted by Top Organizers</h2>
            @if(isset($organizers) && $organizers->isNotEmpty())
                <div class="relative w-full"
                    x-data="{ 
                        interval: null,
                        startAutoScroll() {
                            this.stopAutoScroll();
                            this.interval = setInterval(() => { 
                                if (this.$refs.slider) {
                                    this.$refs.slider.scrollLeft += 1; 
                                    if (this.$refs.slider.scrollLeft + this.$refs.slider.clientWidth >= this.$refs.slider.scrollWidth - 1) {
                                        this.$refs.slider.scrollLeft = 0; 
                                    }
                                }
                            }, 30); 
                        },
                        stopAutoScroll() { clearInterval(this.interval); this.interval = null; }
                    }"
                    x-init="startAutoScroll()"
                    @mouseenter="stopAutoScroll()"
                    @mouseleave="startAutoScroll()"
                    @touchstart.passive="stopAutoScroll()"
                    @touchend.passive="startAutoScroll()"
                    @touchcancel.passive="startAutoScroll()">
                    <div x-ref="slider" class="flex overflow-x-auto pb-8 gap-6 snap-x snap-mandatory px-4 sm:px-6 lg:px-8 -mx-4 sm:-mx-6 lg:-mx-8 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($organizers as $organizer)
                            @php
                                $logoUrl = $organizer->logo?->url ?? null;
                            @endphp
                            <div class="flex-shrink-0 snap-center w-64 h-24 bg-white rounded-2xl flex items-center px-6 gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                                <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-600 group-hover:scale-110 transition-transform overflow-hidden">
                                    @if($logoUrl)
                                        <img src="{{ $logoUrl }}" alt="{{ $organizer->name }}" class="w-full h-full object-contain" loading="lazy">
                                    @else
                                        <span class="font-bold text-lg">{{ mb_substr($organizer->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-800 truncate">{{ $organizer->name }}</h4>
                                    @if($organizer->description)
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider truncate">{{ Str::limit($organizer->description, 32) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="w-full h-24 flex items-center justify-center bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-gray-400 text-sm font-medium">No organizers found.</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Mobile Banner --}}
        @if(isset($mobileBanner) && $mobileBanner)
            @php
                $mobileImgUrl = $mobileBanner->fileBucket?->url
                    ?? (str_starts_with($mobileBanner->image_path ?? '', 'http')
                        ? $mobileBanner->image_path
                        : Storage::url($mobileBanner->image_path ?? ''));
            @endphp
            <div
                x-data="{ dismissed: localStorage.getItem('mobile_banner_{{ $mobileBanner->id }}') === '1' }"
                x-show="!dismissed"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-full"
                class="fixed bottom-0 inset-x-0 z-50 md:hidden"
                x-cloak
            >
                <div class="relative overflow-hidden shadow-[0_-4px_20px_rgba(0,0,0,0.25)]">
                <img src="{{ $mobileImgUrl }}" alt="{{ $mobileBanner->title }}" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/65"></div>
                <div class="relative z-10 flex items-center gap-3 px-4 py-3">
                    <p class="flex-1 text-white font-bold text-sm truncate">{{ $mobileBanner->title }}</p>
                    @if($mobileBanner->link_url)
                        <a href="{{ $mobileBanner->link_url }}"
                           target="{{ $mobileBanner->link_target }}"
                           x-on:click="$wire.call('trackBannerClick', {{ $mobileBanner->id }})"
                           class="flex-shrink-0 bg-[#EE2E24] hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
                            View Now
                        </a>
                    @endif
                    <button
                        @click="dismissed = true; localStorage.setItem('mobile_banner_{{ $mobileBanner->id }}', '1')"
                        class="flex-shrink-0 w-7 h-7 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors"
                        aria-label="Dismiss banner"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <x-footer />

    <!-- Modals -->
    <x-event.filter-modal
        :categories="$categories"
        :active-tab="$activeTab"
        :sort="$sort"
        :date-filter="$dateFilter"
        :min-price="$minPrice"
        :max-price="$maxPrice"
        :selected-categories="$selectedCategories"
        :grouped-categories="$this->groupedCategories"
    />
    <x-event.location-modal :locations="$this->locations" />
</div>
