<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        @if ($empty && !isset($rows[0]))
            <p class="text-sm text-gray-400 py-2">{{ $no_event_label }}</p>
        @elseif (empty($rows))
            <p class="text-sm text-gray-400 py-2">{{ __('event-planner.monitoring.no_ticket_types') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 border-b dark:border-gray-700">
                            <th class="pb-2 pr-3">{{ __('event-planner.monitoring.ticket_type') }}</th>
                            <th class="pb-2 pr-3 text-right">{{ __('event-planner.monitoring.price') }}</th>
                            <th class="pb-2 pr-3 text-right">{{ __('event-planner.monitoring.sold') }}</th>
                            <th class="pb-2 pr-3 text-right">{{ __('event-planner.monitoring.remaining') }}</th>
                            <th class="pb-2 pr-3">{{ __('event-planner.monitoring.fill_rate') }}</th>
                            <th class="pb-2">{{ __('event-planner.monitoring.phase') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($rows as $row)
                            @php
                                $bar = min(100, $row['fill_rate']);
                                $barColor = $bar >= 80 ? 'bg-green-500' : ($bar >= 40 ? 'bg-yellow-400' : 'bg-red-400');
                                $phaseBadge = match($row['phase']) {
                                    'live'     => 'bg-green-100 text-green-700',
                                    'ended'    => 'bg-gray-100 text-gray-500',
                                    'upcoming' => 'bg-blue-100 text-blue-700',
                                    default    => 'bg-indigo-100 text-indigo-700',
                                };
                            @endphp
                            <tr>
                                <td class="py-2 pr-3 font-medium text-gray-800 dark:text-gray-100">{{ $row['name'] }}</td>
                                <td class="py-2 pr-3 text-right text-gray-500">Rp {{ $row['price'] }}</td>
                                <td class="py-2 pr-3 text-right">{{ $row['sold'] }}</td>
                                <td class="py-2 pr-3 text-right text-gray-500">{{ $row['remaining'] }}</td>
                                <td class="py-2 pr-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 dark:bg-gray-700 rounded-full h-2 min-w-[60px]">
                                            <div class="h-2 rounded-full {{ $barColor }}" style="width: {{ $bar }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500 w-12 text-right">{{ $row['fill_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <span class="inline-block rounded px-2 py-0.5 text-xs font-medium {{ $phaseBadge }}">
                                        {{ $row['phase'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
