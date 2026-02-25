<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EventsChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 6;

    protected ?string $maxHeight = '260px';

    public function getHeading(): string
    {
        return 'Total Events';
    }

    protected function getData(): array
    {
        $data = Event::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Events',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#36A2EB', // Blue
                        '#FF6384', // Pink
                        '#4BC0C0', // Teal
                        '#FF9F40', // Orange
                        '#9966FF', // Purple
                    ],
                ],
            ],
            'labels' => $data->pluck('status')->map(fn ($status) => ucfirst($status))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
