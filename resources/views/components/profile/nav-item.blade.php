@props(['tab'])
<a href="#{{ $tab }}" @click.prevent="activeTab = '{{ $tab }}'" :class="activeTab === '{{ $tab }}' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'" {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors']) }}>
    @isset($icon)
        <span class="w-5 h-5">{{ $icon }}</span>
    @endisset
    {{ $slot }}
    @if(isset($badge))
        <span class="ml-auto {{ $badge['class'] ?? 'bg-red-500 text-white text-xs px-2 py-0.5 rounded-full' }}">{{ $badge['text'] }}</span>
    @endif
</a>
