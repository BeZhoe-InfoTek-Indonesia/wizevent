<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        @if ($empty)
            <p class="text-sm text-gray-400 py-2">{{ $empty_label }}</p>
        @else
            <div class="relative pl-6">
                {{-- Vertical line --}}
                <span class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></span>

                @foreach ($items as $item)
                    @php
                        $dotColor = match($item['type_color']) {
                            'success' => 'bg-green-500',
                            'warning' => 'bg-yellow-500',
                            'danger'  => 'bg-red-500',
                            'info'    => 'bg-blue-500',
                            'primary' => 'bg-indigo-500',
                            default   => 'bg-gray-400',
                        };
                    @endphp
                    <div class="relative mb-4 last:mb-0">
                        {{-- Dot --}}
                        <span class="absolute -left-4 top-1.5 h-3 w-3 rounded-full border-2 border-white {{ $dotColor }}"></span>

                        <div class="rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 shadow-sm">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-sm text-gray-800 dark:text-gray-100">
                                    {{ $item['title'] }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $item['start_time'] }} – {{ $item['end_time'] }}
                                    @if ($item['duration'])
                                        ({{ $item['duration'] }} min)
                                    @endif
                                </span>
                                @if ($item['talent_name'])
                                    <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded px-1.5 py-0.5">
                                        🎤 {{ $item['talent_name'] }}
                                    </span>
                                @endif
                            </div>
                            @if ($item['description'])
                                <p class="text-xs text-gray-500 mt-0.5">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
