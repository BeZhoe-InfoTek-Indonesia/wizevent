<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class EventsTrendChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 6,
    ];

    protected ?string $maxHeight = '260px';

    public function getHeading(): string
    {
        return 'Events Created per Month';
    }

    protected function getData(): array
    {
        // Check if Flowframe\Trend is available, otherwise use a simple manual query
        if (class_exists('Flowframe\Trend\Trend')) {
            $data = Trend::model(Event::class)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Events',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'fill' => 'start',
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => $value->date),
            ];
        }

        // Fallback to manual query if Trend package is not installed
        $data = Event::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Events',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
