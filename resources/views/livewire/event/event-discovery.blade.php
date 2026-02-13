<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Hero Banner Carousel -->
        <div 
            x-data="{ 
                active: 0, 
                banners: [
                    { id: 1, image: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070&auto=format&fit=crop', title: 'World Tour 2026', subtitle: 'Experience the magic live in Jakarta' },
                    { id: 2, image: 'https://images.unsplash.com/photo-1459749411177-042180ce673c?q=80&w=2070&auto=format&fit=crop', title: 'Art & Tech Masterclass', subtitle: 'Learn from the industry leaders' },
                    { id: 3, image: 'https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?q=80&w=2074&auto=format&fit=crop', title: 'Music Festival 2026', subtitle: 'Three days of non-stop entertainment' }
                ],
                init() {
                    setInterval(() => {
                        this.active = (this.active + 1) % this.banners.length
                    }, 5000)
                }
            }" 
            class="relative w-full aspect-[21/9] md:aspect-[3/1] rounded-3xl overflow-hidden shadow-2xl group"
        >
            <template x-for="(banner, index) in banners" :key="banner.id">
                <div 
                    x-show="active === index"
                    x-transition:enter="transition ease-out duration-1000"
                    x-transition:enter-start="opacity-0 scale-105"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-1000"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute inset-0"
                >
                    <img :src="banner.image" :alt="banner.title" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-8 md:p-16">
                        <h2 x-text="banner.title" class="text-3xl md:text-5xl font-black text-white mb-2 transform transition-all translate-y-0 opacity-100"></h2>
                        <p x-text="banner.subtitle" class="text-blue-300 text-lg md:text-xl font-bold"></p>
                    </div>
                </div>
            </template>

            <!-- Indicators -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
                <template x-for="(banner, index) in banners" :key="banner.id">
                    <button 
                        @click="active = index"
                        :class="active === index ? 'w-8 bg-blue-600' : 'w-2 bg-white/50 hover:bg-white'"
                        class="h-2 rounded-full transition-all duration-300"
                    ></button>
                </template>
            </div>

            <!-- Nav Arrows -->
            <button @click="active = active === 0 ? banners.length - 1 : active - 1" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="active = (active + 1) % banners.length" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Search & Quick Filters -->
        <div class="bg-white p-1 rounded-2xl shadow-xl shadow-blue-900/5 border border-gray-100 flex flex-col md:flex-row items-stretch gap-1">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="block w-full pl-14 pr-6 py-5 border-none bg-transparent focus:ring-0 text-gray-900 placeholder-gray-400 text-lg" 
                    placeholder="Search events, concerts, and more..."
                >
            </div>
            
            <div class="hidden md:block w-px bg-gray-100 my-3"></div>

            <button 
                wire:click="toggleLocationModal"
                class="flex items-center gap-3 px-8 py-4 text-left group hover:bg-gray-50 transition-colors rounded-xl md:rounded-none"
            >
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Location</p>
                    <p class="text-sm font-bold text-gray-900 truncate max-w-[120px]">{{ $selectedLocation ?? 'Everywhere' }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div class="hidden md:block w-px bg-gray-100 my-3"></div>

            <button 
                wire:click="toggleFilterModal"
                class="flex items-center gap-2 px-6 py-4 text-gray-500 hover:text-blue-600 transition-all font-bold group"
            >
                <div class="p-2 bg-gray-50 rounded-lg group-hover:bg-blue-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
                <span>Filter</span>
            </button>
        </div>

        <!-- Quick Categories -->
        <div class="flex items-center gap-3 overflow-x-auto pb-4 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
            <button 
                wire:click="resetFilters"
                @class([
                    'px-6 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all border shadow-sm',
                    'bg-gray-900 border-gray-900 text-white' => empty($selectedCategories) && !$selectedLocation && $dateFilter === 'all',
                    'bg-white border-gray-200 text-gray-600 hover:border-gray-900 hover:text-gray-900' => !empty($selectedCategories) || $selectedLocation || $dateFilter !== 'all',
                ])
            >
                All Events
            </button>
            @foreach($categories->take(6) as $category)
                @php $isSelected = in_array($category->id, $selectedCategories); @endphp
                <button 
                    wire:click="toggleCategory('{{ $category->id }}')"
                    @class([
                        'px-6 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all border shadow-sm',
                        'bg-blue-600 border-blue-600 text-white' => $isSelected,
                        'bg-white border-gray-200 text-gray-600 hover:border-blue-600 hover:text-blue-600' => !$isSelected,
                    ])
                >
                    {{ $category->name }}
                </button>
            @endforeach
            <button 
                wire:click="setActiveTab('category'); toggleFilterModal();"
                class="px-6 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 flex items-center gap-2"
            >
                More
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>

        <!-- Event Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
            @forelse($events as $event)
                <div class="flex flex-col group">
                    <!-- Image Wrapper -->
                    <div class="relative aspect-[4/5] rounded-2xl overflow-hidden mb-4 shadow-sm group-hover:shadow-xl transition-all duration-500">
                        @if($event->banner)
                            <img src="{{ Storage::url($event->banner->file_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif

                        <!-- Badge Overlay -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            @foreach($event->categories->take(1) as $cat)
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-[10px] font-black uppercase tracking-widest text-blue-600 rounded-md shadow-sm">
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Date Overlay -->
                        <div class="absolute bottom-4 left-4 right-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="bg-black/40 backdrop-blur-md p-3 rounded-xl border border-white/20">
                                <div class="flex items-center gap-3 text-white">
                                    <div class="flex flex-col items-center justify-center bg-white text-gray-900 rounded-lg h-10 w-10 flex-shrink-0">
                                        <span class="text-[10px] font-bold uppercase leading-none">{{ $event->event_date->format('M') }}</span>
                                        <span class="text-lg font-black leading-none">{{ $event->event_date->format('d') }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold truncate">{{ $event->event_date->format('l, Y') }}</p>
                                        <p class="text-[10px] opacity-70 truncate">{{ $event->location }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Overlay -->
                        <div class="absolute top-4 right-4">
                            <button class="w-10 h-10 bg-white/90 backdrop-blur-md rounded-full shadow-lg flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Meta -->
                    <div class="flex-1 space-y-3">
                        <h3 class="text-lg font-black text-gray-900 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2">
                            <a href="{{ route('events.show', $event->slug) }}">
                                {{ $event->title }}
                            </a>
                        </h3>

                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center overflow-hidden flex-shrink-0 border-2 border-white shadow-sm">
                                @if($event->user && $event->user->avatar)
                                    <img src="{{ Storage::url($event->user->avatar) }}" class="h-full w-full object-cover">
                                @else
                                    <span class="text-sm font-bold text-blue-600">{{ substr($event->user?->name ?? '?', 0, 1) }}</span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-gray-500 truncate">{{ $event->user?->name ?? 'Organizer' }}</span>
                        </div>

                        <div class="pt-2 flex items-end justify-between border-t border-gray-50">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Starting from</p>
                                @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                <p class="text-xl font-black text-blue-600">
                                    {{ $minPrice ? 'IDR ' . number_format($minPrice, 0, ',', '.') : 'FREE' }}
                                </p>
                            </div>
                            <a href="{{ route('events.show', $event->slug) }}" class="w-10 h-10 border-2 border-gray-100 rounded-full flex items-center justify-center text-gray-400 group-hover:border-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all transform group-hover:rotate-45">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">No results found</h3>
                    <p class="text-gray-500 max-w-sm">We couldn't find any events matching your criteria. Try adjusting your filters or search terms.</p>
                    <button wire:click="resetFilters" class="mt-8 px-8 py-3 bg-gray-900 text-white font-bold rounded-xl active:scale-95 transition-transform">Reset all filters</button>
                </div>
            @endforelse
        </div>

        @if($events->count() > 0)
            <div class="flex justify-center pt-6">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-white border-2 border-gray-900 text-gray-900 font-black rounded-2xl hover:bg-gray-900 hover:text-white transition-all group">
                    View More Events
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        @endif

        <!-- Promo Banner Section (#PASTIBISA Style) -->
        <div class="relative w-full rounded-2xl overflow-hidden bg-[#001D3D] p-5 md:p-6 flex flex-col md:flex-row items-center gap-6 mt-10">
            <div class="flex-shrink-0 text-center md:text-left md:pl-4">
                <h2 class="text-3xl md:text-4xl font-black italic tracking-tighter text-white leading-none">#PASTIBISA</h2>
                <p class="text-[8px] text-gray-400 mt-2 max-w-[180px] leading-tight uppercase font-medium">Metode pembayaran berizin & diawasi oleh OJK</p>
            </div>
            
            <div class="flex-1 flex overflow-x-auto gap-3 scrollbar-hide pb-1 w-full">
                <!-- Promo Card 1: Diskon -->
                <div class="flex-shrink-0 bg-[#007BFF] rounded-xl p-4 w-52 relative overflow-hidden group border border-white/10">
                    <div class="text-white relative z-10">
                        <div class="flex items-center justify-between mb-0.5">
                            <p class="text-[9px] font-bold opacity-80 uppercase tracking-wider">Diskon Event</p>
                            <span class="bg-orange-500 text-[9px] px-1 py-0.5 rounded font-black text-white">12%</span>
                        </div>
                        <p class="text-2xl font-black tracking-tighter">Rp150<span class="text-sm">rb</span></p>
                        <div class="mt-3 bg-[#FF5630] px-3 py-1.5 rounded-lg inline-block shadow-lg">
                            <p class="text-[10px] font-black tracking-widest uppercase">PASTIBISA26</p>
                        </div>
                    </div>
                </div>

                <!-- Promo Card 2: Cicilan -->
                <div class="flex-shrink-0 bg-[#36B37E] rounded-xl p-4 w-52 relative overflow-hidden group border border-white/10">
                    <div class="text-white relative z-10">
                        <p class="text-[9px] font-bold opacity-80 mb-0.5 uppercase tracking-wider">Cicilan Khusus</p>
                        <p class="text-4xl font-black tracking-tighter mb-0.5">0%</p>
                        <p class="text-[10px] font-bold">Tenor 6 bulan</p>
                    </div>
                </div>

                <!-- Promo Card 3: Cashback -->
                <div class="flex-shrink-0 bg-[#FFAB00] rounded-xl p-4 w-52 relative overflow-hidden group border border-white/10">
                    <div class="text-white relative z-10">
                        <p class="text-[9px] font-bold opacity-80 mb-0.5 uppercase tracking-wider">Promo Spesial</p>
                        <p class="text-2xl font-black tracking-tighter">Rp250<span class="text-sm">rb</span></p>
                        <div class="mt-3 border border-white/40 bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-lg inline-block">
                            <p class="text-[10px] font-black tracking-widest uppercase">AKGPASTIBISA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal (Improved Loket style) -->
    @if($showFilterModal)
        <div class="fixed inset-0 z-[100] overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" wire:click="toggleFilterModal"></div>
            
            <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                <div class="w-screen max-w-md transform transition-all animate-in slide-in-from-right duration-500">
                    <div class="h-full flex flex-col bg-white shadow-2xl overflow-y-auto">
                        <div class="p-8 border-b border-gray-50 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md z-10">
                            <h2 class="text-2xl font-black text-gray-900">Sort & Filter</h2>
                            <button wire:click="toggleFilterModal" class="p-2 bg-gray-50 rounded-full text-gray-400 hover:text-gray-900 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="flex-1 p-8 space-y-10">
                            <!-- Tabs Navigation -->
                            <div class="flex gap-2 p-1 bg-gray-100 rounded-xl">
                                @foreach(['sort' => 'Sort', 'category' => 'Category', 'date' => 'Date', 'price' => 'Price'] as $tab => $label)
                                    <button 
                                        wire:click="setActiveTab('{{ $tab }}')"
                                        @class([
                                            'flex-1 py-2 text-xs font-black uppercase tracking-wider rounded-lg transition-all',
                                            'bg-white text-blue-600 shadow-sm' => $activeTab === $tab,
                                            'text-gray-500 hover:text-gray-900' => $activeTab !== $tab,
                                        ])
                                    >
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>

                            @if($activeTab === 'sort')
                                <div class="space-y-4">
                                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Sort by</h3>
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach(['most_popular' => 'Most Popular', 'newly_added' => 'Newly Added', 'lowest_price' => 'Lowest Price', 'highest_price' => 'Highest Price'] as $val => $label)
                                            <button 
                                                wire:click="$set('sort', '{{ $val }}')"
                                                @class([
                                                    'w-full text-left px-5 py-4 rounded-xl font-bold transition-all flex items-center justify-between',
                                                    'bg-blue-50 text-blue-700 ring-2 ring-blue-600' => $sort === $val,
                                                    'bg-gray-50 text-gray-600 hover:bg-gray-100' => $sort !== $val,
                                                ])
                                            >
                                                {{ $label }}
                                                @if($sort === $val)
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                            @elseif($activeTab === 'category')
                                <div class="space-y-8">
                                    @foreach($this->groupedCategories as $groupName => $groupCategories)
                                        <div class="space-y-4">
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ $groupName }}</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($groupCategories as $category)
                                                    @php $isSelected = in_array($category->id, $selectedCategories); @endphp
                                                    <button 
                                                        wire:click="toggleCategory('{{ $category->id }}')"
                                                        @class([
                                                            'px-4 py-2 rounded-lg text-sm font-bold transition-all border shadow-sm',
                                                            'bg-blue-600 border-blue-600 text-white' => $isSelected,
                                                            'bg-white border-gray-200 text-gray-600 hover:border-blue-600 hover:text-blue-600' => !$isSelected,
                                                        ])
                                                    >
                                                        {{ $category->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @elseif($activeTab === 'price')
                                <div class="space-y-8">
                                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Price Range</h3>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Min (IDR)</label>
                                                <input type="number" wire:model.live="minPrice" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl font-bold text-gray-900 focus:ring-2 focus:ring-blue-600">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Max (IDR)</label>
                                                <input type="number" wire:model.live="maxPrice" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl font-bold text-gray-900 focus:ring-2 focus:ring-blue-600">
                                            </div>
                                        </div>
                                        <input type="range" min="0" max="10000000" step="100000" wire:model.live="maxPrice" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-blue-600">
                                    </div>
                                </div>

                            @elseif($activeTab === 'date')
                                <div class="space-y-6">
                                    @foreach(['all' => 'All Dates', 'today' => 'Today', 'tomorrow' => 'Tomorrow', 'this_weekend' => 'This Weekend', 'other' => 'Custom Range'] as $val => $label)
                                        <button 
                                            wire:click="$set('dateFilter', '{{ $val }}')"
                                            @class([
                                                'w-full text-left px-5 py-4 rounded-xl font-bold transition-all flex items-center justify-between',
                                                'bg-blue-50 text-blue-700 ring-2 ring-blue-600' => $dateFilter === $val,
                                                'bg-gray-50 text-gray-600 hover:bg-gray-100' => $dateFilter !== $val,
                                            ])
                                        >
                                            {{ $label }}
                                            @if($dateFilter === $val)
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            @endif
                                        </button>
                                    @endforeach

                                    @if($dateFilter === 'other')
                                        <div class="grid grid-cols-2 gap-4 pt-4 animate-in fade-in slide-in-from-top-4 duration-300">
                                            <input type="date" wire:model.live="startDate" class="bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-sm focus:ring-2 focus:ring-blue-600">
                                            <input type="date" wire:model.live="endDate" class="bg-gray-50 border-none rounded-xl px-4 py-3 font-bold text-sm focus:ring-2 focus:ring-blue-600">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="p-8 border-t border-gray-50 bg-gray-50/50 sticky bottom-0 flex gap-4">
                            <button wire:click="resetFilters" class="flex-1 px-4 py-4 rounded-xl font-bold text-gray-500 hover:text-gray-900 transition-colors">Reset</button>
                            <button wire:click="applyFilters" class="flex-[2] px-4 py-4 bg-gray-900 text-white font-bold rounded-xl shadow-xl shadow-gray-900/10 active:scale-95 transition-all">Apply Filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Location Modal (Refined) -->
    @if($showLocationModal)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity" wire:click="toggleLocationModal"></div>
            
            <div class="relative bg-white rounded-3xl w-full max-w-2xl max-h-[80vh] flex flex-col shadow-2xl overflow-hidden animate-in zoom-in duration-300">
                <div class="p-8 pb-4 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">Select Location</h2>
                        <p class="text-sm text-gray-400 font-bold">Find events near you</p>
                    </div>
                    <button wire:click="toggleLocationModal" class="p-2 bg-gray-50 rounded-full text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 flex-1 overflow-y-auto">
                    <div class="relative mb-8 group">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-300 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="locationSearch"
                            class="w-full pl-14 pr-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-900 focus:ring-2 focus:ring-blue-600"
                            placeholder="Type city or province..."
                        >
                    </div>

                    <div class="space-y-8">
                        @foreach($this->locations as $group => $items)
                            <div class="space-y-4">
                                <h3 class="text-xs font-black text-gray-300 uppercase tracking-widest">{{ $group }}</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($items as $loc)
                                        <button 
                                            wire:click="selectLocation('{{ $loc }}')"
                                            class="px-5 py-3 text-left rounded-xl text-sm font-bold bg-gray-50 text-gray-600 hover:bg-blue-50 hover:text-blue-700 hover:ring-2 hover:ring-blue-600 transition-all"
                                        >
                                            {{ $loc }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-8 bg-gray-50/50 border-t border-gray-50">
                    <button wire:click="selectLocation(null)" class="w-full py-4 bg-white border-2 border-gray-200 rounded-2xl font-black text-gray-900 hover:bg-gray-900 hover:text-white hover:border-gray-900 transition-all">Clear & Show Everywhere</button>
                </div>
            </div>
        </div>
    @endif

    <x-footer />
</div>
