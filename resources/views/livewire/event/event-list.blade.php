<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Main Content discovery section -->
        <div class="space-y-8">
            <!-- Search Bar & Filters (Mobile & Desktop Integration) -->
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="relative flex-1 w-full group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 sm:text-sm transition duration-150 ease-in-out" 
                        placeholder="Search activities, events or locations"
                    >
                </div>
                
                <button wire:click="toggleFilterModal" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </div>

            <!-- Filter Bar Tags -->
            <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide">
                <button 
                    wire:click="toggleLocationModal"
                    @class([
                        'flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition border group',
                        'text-gray-700 bg-white border-gray-300 hover:bg-gray-50' => !$selectedLocation,
                        'text-white bg-blue-600 border-blue-600' => $selectedLocation,
                    ])
                >
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $selectedLocation ?? 'All locations' }}</span>
                        <svg class="w-3.5 h-3.5 transition group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </button>
                <button 
                    wire:click="toggleFilterModal(); setActiveTab('category')"
                    @class([
                        'px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition border',
                        'text-gray-700 bg-white border-gray-300 hover:bg-gray-50' => empty($selectedCategories),
                        'text-white bg-blue-600 border-blue-600' => !empty($selectedCategories),
                    ])
                >
                    Category {{ !empty($selectedCategories) ? '(' . count($selectedCategories) . ')' : '' }}
                </button>
                <button 
                    wire:click="toggleFilterModal(); setActiveTab('date')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 whitespace-nowrap"
                >
                    Date
                </button>
                <button 
                    wire:click="toggleFilterModal(); setActiveTab('price')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 whitespace-nowrap"
                >
                    Price
                </button>
            </div>

            <!-- Event Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($events as $event)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                        <!-- Image -->
                        <div class="relative aspect-[4/3] bg-gray-200 overflow-hidden">
                            @if($event->banner)
                                <img src="{{ Storage::url($event->banner->file_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-3 right-3">
                                <button class="p-2 bg-white/80 backdrop-blur-sm rounded-full hover:bg-white text-gray-600 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="mb-2">
                                <span class="text-xs font-medium text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Instant Confirmation
                                </span>
                            </div>
                            
                            <h3 class="font-bold text-gray-900 line-clamp-2 min-h-[3rem] mb-2 group-hover:text-blue-600 transition">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <div class="text-sm text-gray-500 mb-1">
                                {{ $event->event_date->format('d M Y') }}
                            </div>
                            <div class="text-sm text-gray-500 truncate mb-3">
                                {{ $event->location }}
                            </div>

                            <div class="flex items-end justify-between mt-4">
                                <div>
                                    @php
                                        $minPrice = $event->ticketTypes->min('price');
                                    @endphp
                                    @if($minPrice)
                                        <div class="text-sm text-red-500 font-bold">
                                            IDR {{ number_format($minPrice, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="text-sm text-green-600 font-bold">Free</div>
                                    @endif
                                    <div class="text-xs text-green-600 font-medium mt-0.5">Available now</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-xl border border-dashed border-gray-300">
                        No results found for your search.
                    </div>
                @endforelse
            </div>

            <!-- Load More Button -->
            @if($events->hasMorePages())
                <div class="text-center mt-12">
                    <button 
                        wire:click="loadMore"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl"
                    >
                        {{ __('welcome.load_more') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Overlay -->
    @if($showFilterModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity" wire:click.self="toggleFilterModal">
            <!-- Modal Container -->
            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl animate-in zoom-in duration-200">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900">Sort & Filter</h2>
                    <button wire:click="toggleFilterModal" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex flex-1 overflow-hidden">
                    <!-- Sidebar Tabs -->
                    <div class="w-1/3 border-r border-gray-100 overflow-y-auto bg-gray-50/50">
                        <div class="p-2 space-y-1">
                            @foreach(['sort', 'category', 'date', 'price'] as $tab)
                                <button 
                                    wire:click="setActiveTab('{{ $tab }}')"
                                    @class([
                                        'w-full text-left px-6 py-4 rounded-xl font-medium transition flex items-center justify-between group',
                                        'text-blue-600 bg-white shadow-sm ring-1 ring-black/5' => $activeTab === $tab,
                                        'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => $activeTab !== $tab,
                                    ])
                                >
                                    {{ ucfirst($tab) }}
                                    @if($activeTab === $tab)
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="flex-1 overflow-y-auto p-8 pt-6">
                        <!-- Selected Filters (Active across all tabs) -->
                        <div class="mb-8 empty:hidden">
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Selected Filters</h3>
                            <div class="flex flex-wrap gap-2">
                                @if(!empty($selectedCategories))
                                    @foreach($selectedCategories as $catId)
                                        <button wire:click="toggleCategory('{{ $catId }}')" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100 group transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3 3 3m0 10l-3 3-3-3m-3-7h14"></path></svg>
                                            {{ $categories->firstWhere('id', $catId)?->name }}
                                            <div class="w-4 h-4 rounded-full bg-blue-200/50 flex items-center justify-center group-hover:bg-blue-200 transition">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                        </button>
                                    @endforeach
                                @endif
                                @if($minPrice > 0 || $maxPrice < 10000000)
                                    <button wire:click="$set('minPrice', 0); $set('maxPrice', 10000000)" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100 group transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        IDR {{ number_format($minPrice, 0) }} - {{ $maxPrice < 10000000 ? number_format($maxPrice, 0) : 'Max' }}
                                        <div class="w-4 h-4 rounded-full bg-blue-200/50 flex items-center justify-center group-hover:bg-blue-200 transition">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    </button>
                                @endif
                                @if($dateFilter !== 'all')
                                    <button wire:click="$set('dateFilter', 'all'); $set('startDate', null); $set('endDate', null)" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100 group transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @if($dateFilter === 'other')
                                            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M') : 'Start' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M') : 'End' }}
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $dateFilter)) }}
                                        @endif
                                        <div class="w-4 h-4 rounded-full bg-blue-200/50 flex items-center justify-center group-hover:bg-blue-200 transition">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    </button>
                                @endif
                            </div>
                            <div class="h-px bg-gray-100 w-full mt-6"></div>
                        </div>

                        @if($activeTab === 'sort')
                            <div class="space-y-6">
                                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Sort By</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach(['nearest' => 'Nearest', 'most_popular' => 'Most Popular', 'lowest_price' => 'Lowest Price', 'highest_price' => 'Highest Price', 'highest_rating' => 'Highest Rating', 'newly_added' => 'Newly Added'] as $val => $label)
                                        <button 
                                            wire:click="$set('sort', '{{ $val }}')"
                                            @class([
                                                'px-4 py-3 rounded-full text-sm font-medium border transition text-center',
                                                'bg-blue-50 border-blue-200 text-blue-700 ring-1 ring-blue-700/10' => $sort === $val,
                                                'bg-white border-gray-200 text-gray-700 hover:border-gray-300 hover:bg-gray-50' => $sort !== $val,
                                            ])
                                        >
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($activeTab === 'category')
                            <div class="space-y-10">
                                @foreach($this->groupedCategories as $groupName => $groupCategories)
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-bold text-gray-900">{{ $groupName }}</h3>
                                            @php
                                                $groupCategoryIds = collect($groupCategories)->pluck('id')->toArray();
                                                $allSelected = collect($groupCategoryIds)->every(fn($id) => in_array($id, $selectedCategories));
                                            @endphp
                                            <input 
                                                type="checkbox" 
                                                wire:click="toggleCategoryGroup([{{ implode(',', array_map(fn($id) => "'$id'", $groupCategoryIds)) }}], {{ $allSelected ? 'false' : 'true' }})"
                                                @if($allSelected) checked @endif
                                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition cursor-pointer"
                                            >
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($groupCategories as $category)
                                                @php $isSelected = in_array($category->id, $selectedCategories); @endphp
                                                <button 
                                                    wire:click="toggleCategory('{{ $category->id }}')"
                                                    @class([
                                                        'flex items-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium border transition',
                                                        'bg-blue-50 border-blue-200 text-blue-700 ring-1 ring-blue-700/10' => $isSelected,
                                                        'bg-white border-gray-200 text-gray-700 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50/50' => !$isSelected,
                                                    ])
                                                >
                                                    @if($isSelected)
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                    @endif
                                                    {{ $category->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($activeTab === 'price')
                            <div class="space-y-8">
                                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Price Range</h3>
                                
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm text-gray-500">Minimum</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">IDR</span>
                                            <input type="number" wire:model.live="minPrice" class="w-full pl-14 pr-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-lg font-bold">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm text-gray-500">Maximum</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">IDR</span>
                                            <input type="number" wire:model.live="maxPrice" class="w-full pl-14 pr-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-lg font-bold">
                                        </div>
                                    </div>
                                </div>

                                <div class="relative mt-8 px-2">
                                    <input type="range" min="0" max="10000000" step="100000" wire:model.live="maxPrice" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                                    <div class="flex justify-between mt-2 text-xs text-gray-400 font-medium">
                                        <span>IDR 0</span>
                                        <span>IDR 10.000.000+</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($activeTab === 'date')
                            <div class="space-y-8">
                                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Select Date</h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach(['all' => 'All Dates', 'today' => 'Today', 'tomorrow' => 'Tomorrow', 'this_weekend' => 'This Weekend', 'other' => 'Other'] as $val => $label)
                                        <button 
                                            wire:click="$set('dateFilter', '{{ $val }}')"
                                            @class([
                                                'px-6 py-3 rounded-full text-sm font-medium border transition',
                                                'bg-blue-50 border-blue-200 text-blue-700 ring-1 ring-blue-700/10' => $dateFilter === $val,
                                                'bg-white border-gray-200 text-gray-700 hover:border-gray-300 hover:bg-gray-50' => $dateFilter !== $val,
                                            ])
                                        >
                                            @if($val === 'other')
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $label }}
                                                </div>
                                            @else
                                                {{ $label }}
                                            @endif
                                        </button>
                                    @endforeach
                                </div>

                                @if($dateFilter === 'other')
                                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 space-y-4 animate-in fade-in slide-in-from-top-4 duration-300">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Start Date</label>
                                                <input type="date" wire:model.live="startDate" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">End Date</label>
                                                <input type="date" wire:model.live="endDate" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p>More filter features coming soon!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-100 flex items-center justify-between bg-white">
                    <button wire:click="resetFilters" class="text-blue-600 font-bold hover:text-blue-700 transition px-4 py-2">
                        Reset
                    </button>
                    <button wire:click="applyFilters" class="px-10 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:scale-[1.02] active:scale-95">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Location Selection Modal -->
    @if($showLocationModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity" wire:click.self="toggleLocationModal">
            <!-- Modal Container -->
            <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col shadow-2xl animate-in zoom-in duration-200">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 pb-2">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="locationSearch"
                            class="block w-full pl-12 pr-4 py-3 border-none bg-gray-100/50 rounded-full focus:ring-2 focus:ring-blue-500/20 focus:bg-white text-gray-900 placeholder-gray-500 transition duration-200" 
                            placeholder="Search destination"
                        >
                    </div>
                    <button wire:click="toggleLocationModal" class="ml-4 p-2 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-6 pt-2">
                    <!-- Location Tools -->
                    <div class="flex gap-3 mb-8">
                        <button wire:click="selectLocation(null)" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            All locations
                        </button>
                    </div>

                    <!-- Location List -->
                    <div class="flex flex-wrap gap-2">
                        @foreach(collect($this->locations)->flatten() as $loc)
                            <button 
                                wire:click="selectLocation('{{ $loc }}')"
                                class="px-4 py-2 text-sm font-medium border border-gray-200 bg-white text-gray-700 rounded-full hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition"
                            >
                                {{ $loc }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <x-footer />
</div>
