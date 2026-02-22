@props([
    'categories',
    'activeTab',
    'sort',
    'dateFilter',
    'minPrice',
    'maxPrice',
    'selectedCategories',
    'groupedCategories',
    'selectedLocation' => null,
    'locations' => []
])

<div
    x-data="{ show: @entangle('showFilterModal') }"
    x-show="show"
    x-on:keydown.escape.window="show = false"
    x-on:open-filter-modal.window="show = true"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-2 sm:p-4"
    style="display: none;"
    @click.self="show = false"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm -z-10"></div>

    {{-- Modal Window --}}
            <div
                    x-transition:enter="ease-out duration-300" 
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                    x-transition:leave="ease-in duration-200" 
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                    x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                    class="relative w-full max-w-4xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col h-[90vh] sm:max-h-[85vh]">
                
                {{-- Header --}}
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Sort & Filter</h2>
                    <button wire:click="toggleFilterModal" class="p-2 -mr-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-all">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Main Content --}}
                <div class="flex flex-1 overflow-hidden">
                    {{-- Sidebar --}}
                    <div class="w-[80px] sm:w-1/3 bg-gray-50/50 border-r border-gray-100 p-2 sm:p-4 space-y-2 overflow-y-auto">
                            @foreach(['sort' => 'Sort', 'location' => 'Location', 'category' => 'Category', 'date' => 'Date', 'price' => 'Price'] as $tab => $label)
                            <button 
                                wire:click="setActiveTab('{{ $tab }}')"
                                @class([
                                    'w-full text-left px-2 sm:px-4 py-3 rounded-xl font-bold text-[11px] sm:text-sm transition-all flex flex-col sm:flex-row items-center sm:justify-between group gap-1',
                                    'bg-white text-red-600 shadow-sm' => $activeTab === $tab,
                                    'text-gray-500 hover:bg-white hover:shadow-sm hover:text-gray-700' => $activeTab !== $tab,
                                ])
                            >
                                <span class="truncate">{{ $label }}</span>
                                @if($activeTab === $tab)
                                    <div class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-red-500 shrink-0"></div>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    {{-- Filtering Content --}}
                    <div class="flex-1 p-3 sm:p-8 overflow-y-auto">
                        
                        {{-- Selected Filters Display --}}
                        @if(!empty($selectedCategories) || $dateFilter !== 'all' || $sort !== 'most_popular' || $minPrice || $maxPrice || $selectedLocation)
                            <div class="mb-8">
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Selected Filters</h3>
                                <div class="flex flex-wrap gap-2">
                                    {{-- Sort --}}
                                    @if($sort !== 'most_popular')
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-bold border border-transparent shadow-sm shadow-red-500/30">
                                            <span>Sort: {{ str_replace('_', ' ', ucfirst($sort)) }}</span>
                                            <button wire:click="$set('sort', 'most_popular')" class="text-red-100 hover:text-white transition-colors"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                        </span>
                                    @endif

                                    {{-- Location --}}
                                    @if($selectedLocation)
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-bold border border-transparent shadow-sm shadow-red-500/30">
                                            <span>Location: {{ $selectedLocation }}</span>
                                            <button wire:click="selectLocation(null)" class="text-red-100 hover:text-white transition-colors"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                        </span>
                                    @endif
                                    
                                    {{-- Date --}}
                                    @if($dateFilter !== 'all')
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-bold border border-transparent shadow-sm shadow-red-500/30">
                                            <span>{{ ucfirst(str_replace('_', ' ', $dateFilter)) }}</span>
                                            <button wire:click="$set('dateFilter', 'all')" class="text-red-100 hover:text-white transition-colors"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                        </span>
                                    @endif

                                    {{-- Categories --}}
                                    @foreach($selectedCategories as $catId)
                                        @php $catName = $categories->find($catId)?->name; @endphp
                                        @if($catName)
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-bold border border-transparent shadow-sm shadow-red-500/30">
                                                <span>{{ $catName }}</span>
                                                <button wire:click="toggleCategory('{{ $catId }}')" class="text-red-100 hover:text-white transition-colors"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="h-px bg-gray-100 w-full mt-6"></div>
                            </div>
                        @endif

                        {{-- CONTENT BASED ON TAB --}}
                        @if($activeTab === 'sort')
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                    @foreach(['nearest' => 'Nearest', 'most_popular' => 'Most Popular', 'lowest_price' => 'Lowest Price', 'highest_price' => 'Highest Price', 'highest_rating' => 'Highest Rating', 'newly_added' => 'Newly Added'] as $val => $label)
                                        <button 
                                            wire:click="$set('sort', '{{ $val }}')"
                                            @class([
                                                'px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-[11px] sm:text-sm font-bold border transition-all text-center flex items-center justify-center min-h-[44px]',
                                                'bg-red-50 border-red-200 text-red-600 ring-1 ring-red-500/10' => $sort === $val,
                                                'bg-white border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50' => $sort !== $val,
                                            ])
                                        >
                                            <span class="line-clamp-2">{{ $label }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                        @elseif($activeTab === 'location')
                            <div class="space-y-6">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        wire:model.live.debounce.300ms="locationSearch"
                                        placeholder="Search city or province..."
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                    >
                                </div>

                                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                                    <button 
                                        wire:click="selectLocation(null)"
                                        @class([
                                            'w-full text-left px-4 py-3 rounded-xl font-bold text-sm transition-all border',
                                            'bg-red-50 border-red-200 text-red-600 shadow-sm' => $selectedLocation === null,
                                            'bg-white border-gray-100 text-gray-600 hover:border-red-200 hover:text-red-500' => $selectedLocation !== null,
                                        ])
                                    >
                                        Everywhere
                                    </button>

                                    @foreach($locations as $group => $locs)
                                        <div class="space-y-2">
                                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">{{ $group }}</h4>
                                            <div class="grid grid-cols-1 gap-2">
                                                @foreach($locs as $location)
                                                    <button 
                                                        wire:click="selectLocation('{{ $location }}')"
                                                        @class([
                                                            'w-full text-left px-4 py-3 rounded-xl font-bold text-sm transition-all border',
                                                            'bg-red-50 border-red-200 text-red-600 shadow-sm' => $selectedLocation === $location,
                                                            'bg-white border-gray-100 text-gray-600 hover:border-red-200 hover:text-red-500' => $selectedLocation !== $location,
                                                        ])
                                                    >
                                                        {{ $location }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        @elseif($activeTab === 'category')
                            <div class="space-y-8">
                                    @foreach($groupedCategories as $groupName => $groupCategories)
                                    <div class="space-y-3">
                                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $groupName }}</h3>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($groupCategories as $category)
                                                @php $isSelected = in_array($category->id, $selectedCategories); @endphp
                                                <button 
                                                    wire:click="toggleCategory('{{ $category->id }}')"
                                                    @class([
                                                        'flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold border transition-all shadow-sm',
                                                        'bg-red-50 border-red-200 text-red-600' => $isSelected,
                                                        'bg-white border-gray-200 text-gray-600 hover:border-red-200 hover:text-red-500' => !$isSelected,
                                                    ])
                                                >
                                                    {{ $category->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @elseif($activeTab === 'date')
                            <div class="space-y-6">
                                <div class="flex flex-wrap gap-3">
                                    @foreach(['all' => 'Any Date', 'today' => 'Today', 'tomorrow' => 'Tomorrow', 'this_weekend' => 'This Weekend', 'other' => 'Specific Dates'] as $val => $label)
                                        <button 
                                            wire:click="$set('dateFilter', '{{ $val }}')"
                                            @class([
                                                'px-6 py-3 rounded-full text-sm font-bold border transition-all shadow-sm',
                                                'bg-red-50 border-red-200 text-red-600' => $dateFilter === $val,
                                                'bg-white border-gray-200 text-gray-600 hover:border-gray-300 hover:bg-gray-50' => $dateFilter !== $val,
                                            ])
                                        >
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>

                                @if($dateFilter === 'other')
                                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 space-y-4 animate-in fade-in slide-in-from-top-4 duration-300">
                                        <div class="grid grid-cols-2 gap-4">
                                             <x-input type="date" wire:model.live="startDate" label="Start" />
                                             <x-input type="date" wire:model.live="endDate" label="End" />
                                         </div>
                                    </div>
                                @endif
                            </div>
                        
                        @elseif($activeTab === 'price')
                            <div class="space-y-8">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Min Price</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">IDR</span>
                                            <input type="number" wire:model.live.debounce.500ms="minPrice" class="w-full pl-14 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500 text-lg font-bold text-gray-900 shadow-sm">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Max Price</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">IDR</span>
                                            <input type="number" wire:model.live.debounce.500ms="maxPrice" class="w-full pl-14 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500 text-lg font-bold text-gray-900 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                                
                                    <div class="relative mt-8 px-2">
                                    <input type="range" min="0" max="10000000" step="100000" wire:model.live="maxPrice" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600">
                                    <div class="flex justify-between mt-2 text-xs text-gray-400 font-bold uppercase tracking-wider">
                                        <span>Min</span>
                                        <span>Max</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-4 sm:p-6 border-t border-gray-100 flex items-center justify-between bg-white z-10 relative">
                    <button wire:click="resetFilters" class="text-gray-500 font-bold hover:text-red-500 transition px-2 sm:px-4 py-2 text-[11px] sm:text-sm uppercase tracking-wider">
                        Reset Filters
                    </button>
                    <button wire:click="applyFilters" class="px-5 sm:px-8 py-2.5 sm:py-3 bg-red-600 text-white font-bold text-[11px] sm:text-sm rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition transform hover:scale-[1.02] active:scale-95">
                        Show Results
                    </button>
                </div>
            </div>
    </div>

