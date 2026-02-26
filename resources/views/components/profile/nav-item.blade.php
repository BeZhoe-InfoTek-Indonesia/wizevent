@props(['tab', 'label', 'icon', 'isNew' => false])

<a
    href="{{ route('profile', ['tab' => $tab]) }}"
    role="tab"
    aria-controls="panel-{{ $tab }}"
    :aria-selected="activeTab === '{{ $tab }}' ? 'true' : 'false'"
    class="flex items-center gap-3.5 px-4 py-3.5 text-xs font-black transition-all rounded-[1.25rem] group w-full relative tracking-widest uppercase"
    :class="activeTab === '{{ $tab }}' 
        ? 'shadow-skeuo-inset bg-white/80 dark:bg-gray-900/40 text-red-600 scale-[0.98]' 
        : 'text-gray-500 dark:text-gray-400 hover:bg-white/40 dark:hover:bg-gray-800/40'"
>
    <div 
        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-300"
        :class="activeTab === '{{ $tab }}' ? 'bg-gradient-to-br from-red-500 to-red-600 text-white shadow-lg shadow-red-500/20 scale-105' : 'bg-gray-50 dark:bg-gray-900 text-gray-400 group-hover:bg-white dark:group-hover:bg-gray-800 group-hover:scale-105 shadow-sm'"
    >
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $icon }}"></path>
        </svg>
    </div>
    
    <span class="flex-1 tracking-[0.1em]">{{ $label }}</span>
    
    @if($isNew)
        <span class="bg-red-500 text-[9px] text-white px-2 py-0.5 rounded-full font-black uppercase tracking-tighter shadow-sm group-hover:animate-pulse border border-white/20">
            {{ __('profile.new') }}
        </span>
    @endif

    <div 
        x-show="activeTab === '{{ $tab }}'"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-y-0"
        x-transition:enter-end="opacity-100 scale-y-100"
        class="absolute left-0 w-1.5 h-7 bg-red-600 rounded-r-full shadow-[0_0_12px_rgba(239,68,68,0.4)]"
    ></div>
</a>
