@php
    use Carbon\Carbon;
@endphp

<div class="bg-[#eef2f6] min-h-screen font-sans text-gray-800 selection:bg-red-500 selection:text-white">

    {{-- 
        SKEUOMORPHISM 2.0: Ambient Light Source 
    --}}
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-br from-white/40 via-transparent to-gray-200/30 z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        {{-- Header & Search Section --}}
        <div class="space-y-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-2">Discover Events</h1>
                    <p class="text-gray-500 font-medium">Find and book the best experiences near you.</p>
                </div>
            </div>

            {{-- 
                CONTROL DECK: Search & Filter 
                Modeled as a floating pill with unified controls.
            --}}
            <div class="relative z-20">
                <div class="bg-gradient-to-br from-white/80 to-white/40 backdrop-blur-[20px] border border-white/80 p-2 md:p-3 rounded-[2.5rem] shadow-[0_20px_40px_-10px_rgba(0,0,0,0.15)] flex flex-col md:flex-row gap-2 md:gap-3 items-center transform transition-transform md:hover:-translate-y-1 duration-300">
                    <div class="flex-grow relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search"
                            class="w-full pl-12 md:pl-14 pr-6 py-3 md:py-4 text-sm md:text-base bg-gray-50/50 border border-gray-200 rounded-full focus:ring-0 focus:border-red-400 focus:bg-white transition-all shadow-inner text-gray-700 placeholder-gray-400 font-medium" 
                            placeholder="Search events, activities, or venues..." 
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
                                <span class="truncate">{{ $selectedLocation ?? 'All Locations' }}</span>
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

            <!-- Active Filters (Pills) -->
            <div class="flex flex-wrap gap-3 items-center">
                @if(!empty($selectedCategories) || $selectedLocation || $dateFilter !== 'all' || $minPrice || $maxPrice)
                     <button 
                        wire:click="resetFilters"
                        class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                    >
                        Reset All
                    </button>
                    
                    @if($selectedLocation)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-gray-200 shadow-sm text-sm font-bold text-gray-700">
                            <span>{{ $selectedLocation }}</span>
                            <button wire:click="selectLocation(null)" class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endif

                    @foreach($selectedCategories as $catId)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-gray-200 shadow-sm text-sm font-bold text-gray-700">
                            <span>{{ $categories->firstWhere('id', $catId)?->name }}</span>
                            <button wire:click="toggleCategory('{{ $catId }}')" class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endforeach
                    
                    @if($dateFilter !== 'all')
                         <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-gray-200 shadow-sm text-sm font-bold text-gray-700">
                            <span>{{ ucfirst(str_replace('_', ' ', $dateFilter)) }}</span>
                            <button wire:click="$set('dateFilter', 'all')" class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endif
                @endif
            </div>
        </div>

        {{-- Event Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
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
                        <h3 class="text-lg font-black text-gray-900 leading-tight mb-3 line-clamp-2 min-h-[3rem] group-hover:text-red-600 transition-colors">
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
                                        {{ $minPrice ? 'IDR ' . number_format($minPrice, 0, ',', '.') : 'FREE' }}
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
                    <div class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">No events found</h3>
                    <p class="text-gray-500 mb-8 max-w-md">We couldn't find any events matching your current filters. Try stripping a few filters or search for something else.</p>
                    <button wire:click="resetFilters" class="px-8 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg shadow-red-500/30 hover:bg-red-600 hover:-translate-y-1 transition-all">Clear All Filters</button>
                </div>
            @endforelse
        </div>

        <!-- Load More Button -->
        @if($events->hasMorePages())
            <div class="text-center pt-8">
                <button 
                    wire:click="loadMore"
                    class="px-10 py-4 bg-white text-gray-900 border border-gray-200 font-bold rounded-full hover:bg-gray-50 transition-all shadow-[0_10px_20px_rgba(0,0,0,0.05)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.1)] hover:-translate-y-1"
                >
                    Load More Events
                </button>
            </div>
        @endif
    </div>

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

    <x-footer />
</div>
