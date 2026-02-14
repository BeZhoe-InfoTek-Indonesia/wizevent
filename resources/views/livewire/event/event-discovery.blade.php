@php
    use Carbon\Carbon;
@endphp

<div class="bg-[#eef2f6] min-h-screen font-sans text-gray-800 selection:bg-red-500 selection:text-white">
    
    {{-- 
        SKEUOMORPHISM 2.0: Ambient Light Source 
        We use a global gradient overlay to simulate a soft light source from the top-left.
    --}}
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-br from-white/40 via-transparent to-gray-200/30 z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-16">
        
        {{-- 
            HERO CAROUSEL: 3D Kinetic Stage
            A floating, interactive stage that cycles through featured content.
        --}}
        {{-- 
            HERO CAROUSEL: The Kinetic Stage (Refined & Asymmetrical)
            Concept: A split-screen composition where text and image are treated as separate physical layers.
            The image is a "floating card" that responds to mouse movement (parallax).
        --}}
        <div 
            x-data="{ 
                activeSlide: 0, 
                slides: [
                    { 
                        id: 1, 
                        image: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070&auto=format&fit=crop', 
                        subtitle: 'Trending Now', 
                        title_line1: 'World',
                        title_line2: 'Tour 2026',
                        description: 'Experience the electric atmosphere of the year\'s biggest global musical phenomenon.',
                        color: 'from-orange-400 to-red-600',
                        date: 'Oct 24',
                        location: 'Jakarta Arena'
                    },
                    { 
                        id: 2, 
                        image: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?q=80&w=2074&auto=format&fit=crop', 
                        subtitle: 'Featured Event', 
                        title_line1: 'Sound',
                        title_line2: 'Of Joy',
                        description: 'A three-day immersive festival celebrating music, art, and community connection.',
                        color: 'from-blue-400 to-purple-600',
                        date: 'Nov 12',
                        location: 'Bali Convention'
                    },
                    { 
                        id: 3, 
                        image: 'https://images.unsplash.com/photo-1459749411177-042180ce673c?q=80&w=2070&auto=format&fit=crop', 
                        subtitle: 'Workshop', 
                        title_line1: 'Master',
                        title_line2: 'Class',
                        description: 'Unlock your creative potential with industry leaders in design and technology.',
                        color: 'from-emerald-400 to-teal-600',
                        date: 'Dec 05',
                        location: 'Bandung Hub'
                    }
                ],
                next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
                init() { setInterval(() => this.next(), 8000) }
            }"
            class="relative h-[750px] w-full bg-[#eef2f6] rounded-[3rem] shadow-[20px_20px_60px_#caced6,-20px_-20px_60px_#ffffff] overflow-hidden border border-white/40 group perspective-1000"
        >
            <!-- Global Ambient Glow (Dynamic) -->
            <div class="absolute inset-0 opacity-30 transition-colors duration-1000"
                 :class="'bg-gradient-to-br ' + slides[activeSlide].color">
            </div>

            <template x-for="(slide, index) in slides" :key="slide.id">
                <div 
                    x-show="activeSlide === index"
                    x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0 lg:translate-x-12"
                    x-transition:enter-end="opacity-100 lg:translate-x-0"
                    x-transition:leave="transition ease-in duration-700"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute inset-0 flex flex-col lg:flex-row items-center justify-between p-8 lg:p-16"
                >
                    <!-- Left: Typography & Info -->
                    <div class="z-20 w-full lg:w-1/2 flex flex-col justify-center items-start space-y-8 pl-4 lg:pl-8">
                        
                        <!-- Badge -->
                        <span 
                            x-text="slide.subtitle" 
                            class="inline-block px-5 py-2 rounded-full text-xs font-black uppercase tracking-widest text-white shadow-lg bg-black/10 backdrop-blur-md border border-white/10"
                        ></span>

                        <!-- Main Title (Broken & Massive) -->
                        <div class="relative">
                            <h1 class="text-6xl lg:text-8xl font-black text-gray-800 leading-[0.85] tracking-tighter drop-shadow-sm mix-blend-multiply opacity-90">
                                <span class="block" x-text="slide.title_line1"></span>
                                <span class="block text-transparent bg-clip-text bg-gradient-to-r" :class="slide.color" x-text="slide.title_line2"></span>
                            </h1>
                            <!-- Decorative Circle behind text -->
                            <div class="absolute -top-10 -left-10 w-32 h-32 rounded-full bg-white/40 blur-3xl -z-10"></div>
                        </div>

                        <!-- Description -->
                        <p x-text="slide.description" class="text-lg text-gray-600 font-medium max-w-md leading-relaxed border-l-4 border-red-500/50 pl-6"></p>

                        <!-- Meta Data Pills -->
                        <div class="flex items-center gap-4 pt-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</span>
                                <span x-text="slide.date" class="text-xl font-black text-gray-800"></span>
                            </div>
                            <div class="w-px h-10 bg-gray-300"></div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Location</span>
                                <span x-text="slide.location" class="text-xl font-black text-gray-800"></span>
                            </div>
                        </div>

                        <!-- Call to Action -->
                        <button class="mt-8 group/btn relative overflow-hidden rounded-2xl bg-gray-900 px-10 py-5 text-white shadow-[10px_10px_20px_rgba(0,0,0,0.2)] transition-all hover:-translate-y-1 hover:shadow-[15px_15px_30px_rgba(0,0,0,0.3)] active:translate-y-0">
                            <div class="relative z-10 flex items-center gap-3 font-bold tracking-wide">
                                <span>GET TICKETS</span>
                                <svg class="w-5 h-5 transition-transform group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </div>
                            <div class="absolute inset-0 -z-0 translate-y-[100%] bg-gradient-to-r from-red-600 to-orange-600 transition-transform duration-300 group-hover/btn:translate-y-0"></div>
                        </button>
                    </div>

                    <!-- Right: Visual 3D Card (Floating) -->
                    <div class="w-full lg:w-1/2 h-full relative flex items-center justify-center pointer-events-none lg:pointer-events-auto">
                        <!-- The Card Itself -->
                        <div class="relative w-[320px] h-[450px] lg:w-[400px] lg:h-[550px] rounded-[3rem] shadow-[30px_30px_60px_rgba(0,0,0,0.25)] border-[8px] border-white bg-gray-200 overflow-hidden transform transition-all duration-700 hover:scale-[1.02] hover:rotate-1 rotate-[-3deg]"
                             style="filter: drop-shadow(0 20px 40px rgba(0,0,0,0.2))">
                             
                            <!-- Image -->
                            <img :src="slide.image" class="absolute inset-0 w-full h-full object-cover">
                            
                            <!-- Gloss Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-white/30 via-transparent to-black/20 mix-blend-overlay"></div>
                            
                            <!-- Floating Badge on Image -->
                            <div class="absolute bottom-8 right-8 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-xl">
                                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                            </div>
                        </div>

                        <!-- Backing Elements (Decorations) -->
                        <div class="absolute -z-10 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-white/20 blur-3xl rounded-full mix-blend-overlay"></div>
                    </div>
                </div>
            </template>

            <!-- Controls (Physical Knobs) -->
            <div class="absolute bottom-8 lg:bottom-12 right-12 lg:right-16 flex gap-4 z-30">
                <button 
                    @click="prev()" 
                    class="w-16 h-16 rounded-full bg-[#eef2f6] shadow-[6px_6px_12px_#caced6,-6px_-6px_12px_#ffffff] flex items-center justify-center text-gray-500 hover:text-red-500 active:shadow-[inset_3px_3px_6px_#caced6,inset_-3px_-3px_6px_#ffffff] active:scale-95 transition-all text-xl"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button 
                    @click="next()" 
                    class="w-16 h-16 rounded-full bg-[#eef2f6] shadow-[6px_6px_12px_#caced6,-6px_-6px_12px_#ffffff] flex items-center justify-center text-gray-500 hover:text-red-500 active:shadow-[inset_3px_3px_6px_#caced6,inset_-3px_-3px_6px_#ffffff] active:scale-95 transition-all text-xl"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-2 bg-red-500/20 w-full">
                <div class="h-full bg-red-500 transition-all duration-300 ease-out shadow-[0_0_10px_rgba(239,68,68,0.5)]" 
                     :style="'width: ' + ((activeSlide + 1) / slides.length * 100) + '%'"></div>
            </div>
        </div>

        {{-- 
            CONTROL DECK: Search & Filter 
            Modeled as a floating pill with unified controls to match reference.
        --}}
        {{-- 
            CONTROL DECK: Search & Filter 
            Modeled as a floating pill with unified controls to match reference.
        --}}
        {{-- 
            HYPER-REALISTIC 3D CONTROL DECK
            Matching the reference: Soft gradient background, floating search bar with recessed inputs.
        --}}
        {{-- 
            CONTROL DECK: Search & Filter 
            Modeled as a floating pill with unified controls to match reference.
        --}}
            <!-- Search Input -->
            <div class="relative z-20 -mt-16 mx-4 md:mx-12 space-y-6">
                <div class="bg-gradient-to-br from-white/80 to-white/40 backdrop-blur-[20px] border border-white/80 p-2 md:p-3 rounded-[2.5rem] shadow-[0_20px_40px_-10px_rgba(0,0,0,0.15)] flex flex-col md:flex-row gap-2 md:gap-3 items-center transform transition-transform md:hover:-translate-y-1 duration-300">
                    <div class="flex-grow relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search"
                            class="w-full pl-12 md:pl-14 pr-6 py-3 md:py-4 text-sm md:text-base bg-gray-50/50 border border-gray-200 rounded-full focus:ring-0 focus:border-red-400 focus:bg-white transition-all shadow-inner text-gray-700 placeholder-gray-400 font-medium" 
                            placeholder="Search events..." 
                            type="text"
                        />
                    </div>
                    <div class="flex w-full md:w-auto gap-2 md:gap-3">
                        <div class="relative flex-1 md:w-56 group">
                            <div class="absolute inset-y-0 left-0 pl-4 md:pl-6 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <button 
                                wire:click="toggleLocationModal"
                                class="w-full pl-10 md:pl-14 pr-8 md:pr-10 py-3 md:py-4 text-sm md:text-base bg-gray-50/50 border border-gray-200 rounded-full focus:ring-0 focus:border-red-400 focus:bg-white transition-all shadow-inner text-gray-700 font-medium text-left truncate flex items-center justify-between"
                            >
                                <span class="truncate">{{ $selectedLocation ?? 'Everywhere' }}</span>
                            </button>
                            <div class="absolute inset-y-0 right-0 pr-4 md:pr-6 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                        <button 
                            wire:click="toggleFilterModal"
                            class="bg-gradient-to-br from-red-500 to-red-600 border border-white/20 text-white shadow-[0_10px_20px_-5px_rgba(220,38,38,0.2)] md:shadow-[0_10px_15px_-3px_rgba(220,38,38,0.3),0_4px_6px_-2px_rgba(220,38,38,0.1),inset_0_2px_4px_rgba(255,255,255,0.3)] hover:from-red-400 hover:to-red-500 hover:-translate-y-0.5 transition-all px-6 py-3 md:px-8 md:py-4 rounded-full font-bold flex items-center gap-2 whitespace-nowrap text-sm md:text-base"
                        >
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                            <span>Filters</span>
                        </button>
                    </div>
                </div>
            </div>

        {{-- 
            RECENTLY SEARCHED: Clean White Pills
            Matching the user's reference image exactly: "RECENTLY SEARCHED" label, white pills with red icons.
        --}}
        <div class="px-2 max-w-screen-xl mx-auto">
            <h2 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-1">Recently Searched</h2>
            
            <div class="flex flex-wrap gap-3">
                @foreach(['Rock Concerts', 'Jazz Night', 'Food Festival NYC', 'Stand-up Comedy'] as $search)
                    <button 
                        wire:click="$set('search', '{{ $search }}')"
                        class="group px-6 py-3 rounded-full bg-white text-[13px] font-bold text-gray-700 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all outline-none border border-transparent flex items-center gap-2.5"
                    >
                        <svg class="w-4 h-4 text-[#EE2E24]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $search }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- 
            HOT DEALS SECTION
            Two distinctive cards: Early Bird (Red) & VIP Access (Dark).
        --}}
        <div class="space-y-6 mt-12 mb-12">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-3xl font-black text-gray-800 tracking-tight">Hot Deals</h2>
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1: Early Bird Special (Red Gradient) -->
                <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#e64747] to-[#e66e47] p-8 md:p-10 text-white shadow-lg shadow-red-500/20 group">
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
                        
                        <button class="bg-white text-red-500 px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-gray-50 active:scale-95 transition-all">
                            Claim Now
                        </button>
                    </div>
                </div>

                <!-- Card 2: VIP Access (Dark) -->
                <div class="relative overflow-hidden rounded-[2.5rem] bg-[#1e2330] p-8 md:p-10 text-white shadow-lg shadow-gray-900/10 group">
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
                        
                        <button class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-red-900/30 hover:brightness-110 active:scale-95 transition-all">
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

        {{-- 
            EVENT CARDS: Physical Objects
            "Floating" cards with thick borders and realistic physics.
        --}}
        {{-- 
            EVENT CARDS: Modern Ticket Style
            Matching the user's "Upcoming Events" reference image.
        --}}
        <div class="space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between px-2">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Upcoming Events</h2>
                <a href="{{ route('events.index') }}" class="text-red-500 font-bold hover:text-red-600 transition flex items-center gap-1 text-sm">
                    View all <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($events as $event)
                    <div class="group relative bg-white rounded-[2.5rem] p-3 shadow-[0_10px_30px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_40px_-5px_rgba(0,0,0,0.1)] transition-all duration-300 hover:-translate-y-2 cursor-pointer border border-gray-100">
                        
                        <!-- Image Container -->
                        <div class="relative aspect-[4/3] rounded-[2rem] overflow-hidden mb-5">
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
                        <div class="px-2 pb-2">
                            <!-- Date -->
                            <div class="flex items-center gap-2 text-red-500 font-bold text-[10px] uppercase tracking-wider mb-3">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $event->event_date->format('D, j F • h:i A') }}
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-black text-gray-900 leading-tight mb-3 line-clamp-2 min-h-[3rem]">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <!-- Location -->
                            <div class="flex items-center gap-2 mb-6">
                                <div class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-400 truncate">{{ $event->location }}</span>
                            </div>

                            <!-- Ticket Notch Divider -->
                            <div class="relative pt-4 border-t border-dashed border-gray-200">
                                <!-- Notches -->
                                <div class="absolute -left-[1.35rem] -top-3 w-6 h-6 rounded-full bg-[#eef2f6]"></div>
                                <div class="absolute -right-[1.35rem] -top-3 w-6 h-6 rounded-full bg-[#eef2f6]"></div>
                                
                                <!-- Price & Action -->
                                <div class="flex items-center justify-between mt-1">
                                    <div>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Starting from</p>
                                        @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                        <p class="text-xl font-black text-gray-900">
                                            {{ $minPrice ? '$' . number_format($minPrice, 2) : 'FREE' }}
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
                     <a href="{{ route('events.index') }}" class="inline-flex items-center gap-3 px-10 py-4 rounded-full bg-white text-gray-800 font-bold shadow-[0_4px_15px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                        View More Events
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Trending Now Section -->
    <div class="bg-gray-900 text-white w-full py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[500px] h-[500px] bg-red-600/20 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-3xl opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-2 h-10 bg-red-500 rounded-full shadow-[0_0_15px_rgba(239,68,68,0.8)]"></div>
                <h2 class="text-4xl font-extrabold text-white drop-shadow-lg">Trending Now</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[280px]">
                <!-- Large Featured Card -->
                <div class="md:col-span-2 md:row-span-2 relative rounded-[2.5rem] overflow-hidden group cursor-pointer border border-white/10 shadow-2xl">
                    <img alt="Concert" class="w-full h-full object-cover transition duration-700 group-hover:scale-105 group-hover:rotate-1" src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070&auto=format&fit=crop"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-90"></div>
                    <div class="absolute top-6 right-6 bg-red-600 text-white text-[10px] font-black uppercase px-4 py-2 rounded-lg shadow-[0_0_20px_rgba(220,38,38,0.6)] animate-pulse border border-red-400">
                        Selling Fast 🔥
                    </div>
                    <div class="absolute bottom-0 left-0 p-10 max-w-2xl">
                        <div class="text-red-400 font-bold mb-3 uppercase tracking-widest text-sm drop-shadow-sm">Music Festival</div>
                        <h3 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">Electric Dreams 2026</h3>
                        <p class="text-gray-300 mb-6 text-lg line-clamp-2 leading-relaxed">Experience the biggest electronic music festival of the year with top DJs from around the globe.</p>
                        <button class="bg-gradient-to-r from-red-500 to-red-600 text-white border border-white/20 inline-flex items-center px-8 py-4 rounded-2xl font-bold transition-all hover:scale-105 hover:shadow-[0_0_20px_rgba(220,38,38,0.5)]">
                            Get Tickets <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
                
                <!-- Small Card 1 -->
                <div class="relative rounded-[2.5rem] overflow-hidden group cursor-pointer border border-white/10 shadow-lg">
                    <img alt="Comedy" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" src="https://images.unsplash.com/photo-1616781297371-332924dc5280?q=80&w=2070&auto=format&fit=crop"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-90"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <div class="text-red-400 font-bold text-[10px] mb-2 uppercase tracking-wide">Stand-up Comedy</div>
                        <h3 class="text-2xl font-bold text-white group-hover:text-red-400 transition-colors">Laugh Out Loud</h3>
                    </div>
                </div>
                
                <!-- Small Card 2 -->
                <div class="relative rounded-[2.5rem] overflow-hidden group cursor-pointer border border-white/10 shadow-lg">
                    <img alt="Workshop" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?q=80&w=2070&auto=format&fit=crop"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-90"></div>
                    <div class="absolute top-4 right-4 bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] font-bold uppercase px-3 py-1.5 rounded-lg">
                        Last Few Seats
                    </div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <div class="text-red-400 font-bold text-[10px] mb-2 uppercase tracking-wide">Workshop</div>
                        <h3 class="text-2xl font-bold text-white group-hover:text-red-400 transition-colors">Creative Design Summit</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="relative z-10 py-20 bg-gradient-to-br from-[#e0e7ff] to-[#f3f4f6] border-y border-white/50 shadow-[inset_0_2px_10px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-16 text-center drop-shadow-sm">What Fans Are Saying</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <!-- Card 1: Paper Note (Rotated Left) -->
                <div class="relative group">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-red-400 shadow-md border-2 border-white/50 z-20"></div>
                    <div class="h-full bg-gradient-to-b from-[#fffcf5] to-white p-8 rounded-2xl shadow-[0_4px_6px_rgba(0,0,0,0.05),0_10px_15px_rgba(0,0,0,0.1)] border border-black/5 transform rotate-2 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center gap-1 mb-4 text-yellow-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-gray-600 italic mb-6 leading-relaxed text-sm font-serif">"The booking process was incredibly smooth. I loved the 3D venue view, it really helped me pick the perfect seat!"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-white shadow-md overflow-hidden">
                                <img src="https://i.pravatar.cc/150?u=a042581f4e29026024d" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Sarah Jenkins</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">New York, NY</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Glass Panel (Straight) -->
                <div class="relative group">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-gray-400 shadow-md border-2 border-white/50 z-20"></div>
                    <div class="h-full bg-white/60 backdrop-blur-xl border border-white/70 p-8 rounded-2xl shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05),0_2px_4px_-1px_rgba(0,0,0,0.03),inset_0_1px_2px_rgba(255,255,255,0.8)] transform -rotate-1 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center gap-1 mb-4 text-yellow-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-gray-700 italic mb-6 leading-relaxed text-sm font-serif">"TicketRed is my go-to for all concerts. The exclusive pre-sale access for members is totally worth it."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-white shadow-md overflow-hidden">
                                <img src="https://i.pravatar.cc/150?u=4" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Michael Chen</p>
                                <p class="text-[10px] text-gray-500 font-bold uppercase">San Francisco, CA</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Paper Note (Rotated Right) -->
                <div class="relative group">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-blue-400 shadow-md border-2 border-white/50 z-20"></div>
                    <div class="h-full bg-gradient-to-b from-[#fffcf5] to-white p-8 rounded-2xl shadow-[0_4px_6px_rgba(0,0,0,0.05),0_10px_15px_rgba(0,0,0,0.1)] border border-black/5 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center gap-1 mb-4 text-yellow-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-gray-600 italic mb-6 leading-relaxed text-sm font-serif">"Got tickets for the whole family for the magic show. The QR code entry system was super fast and convenient."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-white shadow-md overflow-hidden">
                                <img src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="User" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Emma Wilson</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">London, UK</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizers Section -->
    <div class="relative z-10 py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         <h2 class="text-2xl font-bold text-gray-700 mb-12 text-center opacity-80">Trusted by Top Organizers</h2>
         <div class="relative w-full" 
              x-data="{ 
                  interval: null,
                  startAutoScroll() { 
                      this.interval = setInterval(() => { 
                          if (this.$refs.slider) {
                              // Smooth scroll increment
                              this.$refs.slider.scrollLeft += 1; 
                              // Reset if reached end (simple loop)
                              if (this.$refs.slider.scrollLeft + this.$refs.slider.clientWidth >= this.$refs.slider.scrollWidth - 1) {
                                  this.$refs.slider.scrollLeft = 0; 
                              }
                          }
                      }, 30); 
                  },
                  stopAutoScroll() { clearInterval(this.interval) }
              }" 
              x-init="startAutoScroll()"
              @mouseenter="stopAutoScroll()" 
              @mouseleave="startAutoScroll()">
            <div x-ref="slider" class="flex overflow-x-auto pb-8 gap-6 snap-x snap-mandatory px-4 sm:px-6 lg:px-8 -mx-4 sm:-mx-6 lg:-mx-8 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                <!-- Ticketmaster -->
                <div class="flex-shrink-0 snap-center w-64 h-24 bg-white rounded-2xl flex items-center px-6 gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2a3 3 0 003 3v2a3 3 0 00-3 3v2h-9v-2a3 3 0 00-3-3v-2a3 3 0 003-3V5h9z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Ticketmaster</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Global Partner</p>
                    </div>
                </div>

                <!-- Live Nation -->
                <div class="flex-shrink-0 snap-center w-64 h-24 bg-white rounded-2xl flex items-center px-6 gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Live Nation</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Music Events</p>
                    </div>
                </div>

                <!-- Eventbrite -->
                <div class="flex-shrink-0 snap-center w-64 h-24 bg-white rounded-2xl flex items-center px-6 gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Eventbrite</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Workshops</p>
                    </div>
                </div>

                <!-- AEG Presents -->
                <div class="flex-shrink-0 snap-center w-64 h-24 bg-white rounded-2xl flex items-center px-6 gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">AEG Presents</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Concerts</p>
                    </div>
                </div>

                <!-- StubHub -->
                <div class="flex-shrink-0 snap-center w-64 h-24 bg-white rounded-2xl flex items-center px-6 gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">StubHub</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Resale Market</p>
                    </div>
                </div>

                 <!-- Feat -->
                 <div class="flex-shrink-0 snap-center w-64 h-24 bg-white rounded-2xl flex items-center px-6 gap-4 shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                    <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center text-pink-600 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Fever</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Local Experiences</p>
                    </div>
                </div>
            </div>
        </div>

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
