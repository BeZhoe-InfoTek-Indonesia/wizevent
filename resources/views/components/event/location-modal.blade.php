@props(['locations'])

<div
    x-data="{ show: @entangle('showLocationModal') }"
    x-show="show"
    x-on:keydown.escape.window="show = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm"
    style="display: none;"
    @click.self="show = false"
>
    <div class="bg-white rounded-[2rem] w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col shadow-2xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 pb-2">
            <div class="relative flex-1 group">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="locationSearch"
                    class="block w-full pl-12 pr-4 py-4 border-none bg-gray-100/50 rounded-full focus:ring-0 text-gray-900 placeholder-gray-500 transition duration-200 font-medium"
                    placeholder="Search cities or venues..."
                >
            </div>
            <button wire:click="toggleLocationModal" class="ml-4 p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto p-6 pt-2">
            <div class="flex gap-3 mb-6">
                <button wire:click="selectLocation(null)" class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    Anywhere
                </button>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach(collect($locations)->flatten() as $loc)
                    <button
                        wire:click="selectLocation('{{ $loc }}')"
                        class="px-4 py-2 text-sm font-bold border border-gray-200 bg-white text-gray-700 rounded-full hover:border-red-500 hover:text-red-600 hover:bg-red-50 transition"
                    >
                        {{ $loc }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
