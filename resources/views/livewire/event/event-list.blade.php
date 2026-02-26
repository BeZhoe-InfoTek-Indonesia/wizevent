@php
    use Carbon\Carbon;
@endphp

<div class="from-gray-50 via-white to-gray-100 min-h-screen font-sans text-gray-900">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                <a href="/" class="hover:text-red-500 transition-colors">Home</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-red-500 font-semibold">Events</span>
            </nav>
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black text-gray-900 mb-2">Discover Events</h1>
                    <p class="text-gray-600">Found {{ $events->total() }} events in your area</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Mobile Search and Filter --}}
                    <div class="flex lg:hidden items-center gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Search..."
                                class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                            />
                        </div>
                        <button 
                            wire:click="toggleFilterModal"
                            class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            Filter
                        </button>
                    </div>

                    <div class="hidden lg:flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Sort by:</span>
                        <select wire:model.live="sort" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                            <option value="most_popular">Popularity</option>
                            <option value="date">Date</option>
                            <option value="lowest_price">Price: Low to High</option>
                            <option value="highest_price">Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            {{-- Sidebar Filters --}}
            <aside class="hidden lg:block lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6 space-y-6">
                    
                    {{-- Search --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Search events..."
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                            />
                        </div>
                    </div>

                    {{-- Date Filter --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">Date</label>
                        <div class="space-y-3">
                            {{-- Quick Date Filters --}}
                            <div class="space-y-2">
                                <button wire:click="$set('dateFilter', 'today')" class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ $dateFilter === 'today' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                                    Today
                                </button>
                                <button wire:click="$set('dateFilter', 'tomorrow')" class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ $dateFilter === 'tomorrow' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                                    Tomorrow
                                </button>
                                <button wire:click="$set('dateFilter', 'this_week')" class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ $dateFilter === 'this_week' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                                    This Week
                                </button>
                            </div>

                            {{-- Custom Date Range --}}
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Custom Range</p>
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1.5">Start Date</label>
                                        <input 
                                            type="date" 
                                            wire:model.live="startDate"
                                            wire:change="$set('dateFilter', 'other')"
                                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1.5">End Date</label>
                                        <input 
                                            type="date" 
                                            wire:model.live="endDate"
                                            wire:change="$set('dateFilter', 'other')"
                                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Location Filter --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">Location</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <select 
                                wire:model.live="selectedLocation" 
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all appearance-none"
                            >
                                <option value="">All Locations</option>
                                @foreach($this->locations as $group => $locs)
                                    <optgroup label="{{ $group }}">
                                        @foreach($locs as $location)
                                            <option value="{{ $location }}">{{ $location }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Price Range --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">Price Range</label>
                        <div class="space-y-4">                        
                            {{-- Range Slider --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>Rp {{ number_format($minPrice ?? 0, 0, ',', '.') }}</span>
                                    <span class="text-gray-400">—</span>
                                    <span>Rp {{ number_format($maxPrice ?? 10000000, 0, ',', '.') }}</span>
                                </div>
                                <div class="relative">
                                    <input 
                                        type="range" 
                                        wire:model.live="minPrice" 
                                        min="0" 
                                        max="10000000" 
                                        step="50000" 
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-500"
                                    />
                                </div>
                            </div>

                            {{-- Price Input Fields --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1.5">Min Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-gray-500 font-medium">Rp</span>
                                        <input 
                                            type="number" 
                                            wire:model.live.debounce.500ms="minPrice"
                                            min="0"
                                            step="10000"
                                            placeholder="0"
                                            class="w-full pl-11 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1.5">Max Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-gray-500 font-medium">Rp</span>
                                        <input 
                                            type="number" 
                                            wire:model.live.debounce.500ms="maxPrice"
                                            min="0"
                                            step="10000"
                                            placeholder="10000000"
                                            class="w-full pl-11 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Event Type --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">Event Type</label>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($categories as $category)
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="selectedCategories" 
                                        value="{{ $category->id }}"
                                        class="w-4 h-4 text-red-500 border-gray-300 rounded focus:ring-red-500"
                                    />
                                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                    <span class="ml-auto text-xs text-gray-400">({{ $category->events_count ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Reset Button --}}
                    @if(!empty($selectedCategories) || $selectedLocation || $dateFilter !== 'all' || $minPrice || $maxPrice)
                        <button 
                            wire:click="resetFilters"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset Filters
                        </button>
                    @endif
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="lg:col-span-3">
                
                {{-- Active Filters Pills --}}
                @if(!empty($selectedCategories) || $selectedLocation || $dateFilter !== 'all')
                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($selectedLocation)
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 shadow-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $selectedLocation }}
                                <button wire:click="selectLocation(null)" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </span>
                        @endif

                        @foreach($selectedCategories as $catId)
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 shadow-sm">
                                {{ $categories->firstWhere('id', $catId)?->name }}
                                <button wire:click="toggleCategory('{{ $catId }}')" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </span>
                        @endforeach
                        
                        @if($dateFilter !== 'all')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 shadow-sm">
                                {{ ucfirst(str_replace('_', ' ', $dateFilter)) }}
                                <button wire:click="$set('dateFilter', 'all')" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Event Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($events as $event)
                        <article class="relative group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            
                            {{-- Image --}}
                            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                                @if($event->banner)
                                    <img 
                                        src="{{ Storage::url($event->banner->file_path) }}" 
                                        alt="{{ $event->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                
                                {{-- Category Badge --}}
                                <div class="absolute top-3 left-3">
                                    <span class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-bold uppercase tracking-wide rounded-lg shadow-lg">
                                        {{ $event->categories->first()?->name ?? 'Event' }}
                                    </span>
                                </div>

                                {{-- Wishlist Button --}}
                                <button 
                                    wire:click="toggleFavorite({{ $event->id }})"
                                    class="z-10 absolute top-3 right-3 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center transition-all shadow-lg {{ $this->isFavorite($event->id) ? 'text-red-500 hover:text-red-600' : 'text-gray-600 hover:text-red-500 hover:bg-white' }}"
                                >
                                    @if($this->isFavorite($event->id))
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @endif
                                </button>
                            </div>

                            {{-- Content --}}
                            <div class="p-5">
                                {{-- Date & Time --}}
                                <div class="flex items-center gap-2 text-red-500 text-xs font-bold uppercase tracking-wide mb-3">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $event->event_date->format('M d, Y') }}</span>
                                    <span class="text-gray-300">•</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $event->event_date->format('h:i A') }}</span>
                                </div>

                                {{-- Title --}}
                                <h3 class="text-base font-black text-gray-900 mb-0 line-clamp-2 group-hover:text-red-600 transition-colors">
                                    <a href="{{ route('events.show', $event->slug) }}" class="before:absolute before:inset-0">{{ $event->title }}</a>
                                </h3>

                                {{-- Location --}}
                                <div class="flex items-start gap-2 mb-4">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-sm text-gray-600 line-clamp-1">{{ $event->location }}</span>
                                </div>

                                {{-- Divider --}}
                                <div class="relative pt-5 mt-4">
                                    <!-- Dashed Line -->
                                    <div class="absolute left-0 right-0 top-0 border-t-2 border-dashed border-gray-100"></div>
                                    <!-- Ticket Cutouts -->
                                    <div class="absolute -left-8 -top-3 w-6 h-6 bg-gray-50 rounded-full"></div>
                                    <div class="absolute -right-8 -top-3 w-6 h-6 bg-gray-50 rounded-full"></div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col justify-center">
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Starting from</p>
                                            @php $minPrice = $event->ticketTypes->min('price'); @endphp
                                            <p class="text-2xl font-black text-gray-900 tracking-tight leading-none">
                                                @if($minPrice)
                                                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                                                @else
                                                    <span class="text-green-500">FREE</span>
                                                @endif
                                            </p>
                                        </div>
                                        <a 
                                            href="{{ route('events.show', $event->slug) }}" 
                                            class="z-10 relative flex flex-shrink-0 items-center justify-center w-12 h-12 bg-red-50 text-red-500 rounded-full hover:bg-red-500 hover:text-white transition-all duration-300 group/btn"
                                        >
                                            <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full py-20 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 mb-2">No events found</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">We couldn't find any events matching your filters. Try adjusting your search criteria.</p>
                            <button 
                                wire:click="resetFilters" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-red-500/30"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Clear All Filters
                            </button>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($events->hasMorePages())
                    <div class="mt-12 text-center">
                        <button 
                            wire:click="loadMore"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-white border-2 border-gray-200 text-gray-900 font-bold rounded-xl hover:border-red-500 hover:text-red-500 transition-all shadow-sm hover:shadow-md"
                        >
                            <span>Load More Events</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                @endif
            </main>
        </div>
    </div>

    {{-- Modals --}}
    <x-event.filter-modal 
        :categories="$categories" 
        :active-tab="$activeTab" 
        :sort="$sort" 
        :date-filter="$dateFilter" 
        :min-price="$minPrice" 
        :max-price="$maxPrice" 
        :selected-categories="$selectedCategories" 
        :grouped-categories="$this->groupedCategories"
        :selected-location="$selectedLocation"
        :locations="$this->locations"
    />
    <x-event.location-modal :locations="$this->locations" />

    <x-footer />

    {{-- Notifications (SweetAlert2 Toast) --}}
    {{-- Wrapped in DOMContentLoaded because app.js is a type="module" (deferred) script --}}
    {{-- and window.Swal is not available until after module scripts execute. --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            window.showNotification = function(message, type = 'info') {
                Toast.fire({
                    icon: type,
                    title: message,
                });
            };

            document.addEventListener('livewire:init', () => {
                // Listen for wishlist events
                Livewire.on('added-to-wishlist', (data) => {
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Event added to wishlist',
                    });
                });

                Livewire.on('removed-from-wishlist', (data) => {
                    Toast.fire({
                        icon: 'info',
                        title: data.message || 'Event removed from wishlist',
                    });
                });

                Livewire.on('show-login-modal', () => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Login Required',
                        text: 'Please login to add events to your wishlist.',
                        confirmButtonText: 'Login',
                        confirmButtonColor: '#EE2E24',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('login') }}';
                        }
                    });
                });
            });
        });
    </script>
</div>
