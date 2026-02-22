<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Order;
use App\Models\Organizer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopOrganizersWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $pollingInterval = '60s';

    protected int | string | array $columnSpan = 2;

    public ?string $filter = 'revenue';

    public ?string $period = 'last_30_days';

    public function getHeading(): string
    {
        return __('admin.top_organizers_heading');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\SelectAction::make('filter')
                ->label(__('admin.filter_by'))
                ->options([
                    'revenue' => __('admin.revenue'),
                    'events' => __('admin.events'),
                    'orders' => __('admin.orders'),
                ])
                ->default('revenue')
                ->selectablePlaceholder(false)
                ->action(function (string $data) {
                    $this->filter = $data;
                    $this->updateChartData();
                }),

            \Filament\Actions\SelectAction::make('period')
                ->label(__('admin.period'))
                ->options([
                    'last_7_days' => __('admin.last_7_days'),
                    'last_30_days' => __('admin.last_30_days'),
                    'last_90_days' => __('admin.last_90_days'),
                    'this_year' => __('admin.this_year'),
                ])
                ->default('last_30_days')
                ->selectablePlaceholder(false)
                ->action(function (string $data) {
                    $this->period = $data;
                    $this->updateChartData();
                }),
        ];
    }

    protected function getData(): array
    {
        $startDate = now();
        $endDate = now();

        switch ($this->period) {
            case 'last_7_days':
                $startDate = now()->subDays(7);
                break;
            case 'last_30_days':
                $startDate = now()->subDays(30);
                break;
            case 'last_90_days':
                $startDate = now()->subDays(90);
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                break;
        }

        $filterType = $this->filter;

        $topOrganizers = Organizer::withCount('events')
            ->with(['events' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('events.created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($organizer) use ($startDate, $endDate, $filterType) {
                $eventIds = $organizer->events->pluck('id');
                
                $revenue = Order::whereIn('event_id', $eventIds)
                    ->where('status', 'completed')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->sum('total_amount');
                
                $orders = Order::whereIn('event_id', $eventIds)
                    ->where('status', 'completed')
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->count();

                return [
                    'name' => $organizer->name,
                    'revenue' => (float) $revenue,
                    'events' => $organizer->events_count,
                    'orders' => $orders,
                ];
            })
            ->sortByDesc($filterType)
            ->take(10);

        return [
            'datasets' => [
                [
                    'label' => $this->getFilterLabel(),
                    'data' => $topOrganizers->pluck($this->filter)->toArray(),
                    'backgroundColor' => [
                        '#36A2EB',
                        '#FF6384',
                        '#4BC0C0',
                        '#FF9F40',
                        '#9966FF',
                        '#FFCD56',
                        '#4BC0C0',
                        '#FF6384',
                        '#36A2EB',
                        '#FF9F40',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $topOrganizers->pluck('name')->toArray(),
        ];
    }

    protected function getFilterLabel(): string
    {
        return match ($this->filter) {
            'revenue' => __('admin.revenue'),
            'events' => __('admin.events'),
            'orders' => __('admin.orders'),
            default => __('admin.revenue'),
        };
    }

    public function updateChartData(): void
    {
        $this->dispatch('update-chart');
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": ";
                            }
                            if (context.parsed.y !== null) {
                                if (context.dataset.label.includes("Revenue")) {
                                    label += "Rp " + new Intl.NumberFormat("id-ID").format(context.parsed.y);
                                } else {
                                    label += context.parsed.y;
                                }
                            }
                            return label;
                        }',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) {
                            if (value >= 1000000) {
                                return "Rp " + (value / 1000000).toFixed(1) + "M";
                            } else if (value >= 1000) {
                                return "Rp " + (value / 1000).toFixed(0) + "K";
                            } else {
                                return value;
                            }
                        }',
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
