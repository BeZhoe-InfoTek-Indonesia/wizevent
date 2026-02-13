@props(['title' => null])
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
    @if($title)
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
