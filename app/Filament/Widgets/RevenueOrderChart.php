<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueOrderChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 6,
    ];

    protected ?string $maxHeight = '320px';

    public ?string $filter = 'last_30_days';

    public ?string $year = null;

    public ?string $startDate = null;

    public ?string $endDate = null;

    public function getHeading(): string
    {
        return __('admin.revenue_chart_heading');
    }

    protected function getHeaderActions(): array
    {
        $years = Order::select(DB::raw('YEAR(created_at) as year'))
            ->where('status', 'completed')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year', 'year')
            ->toArray();

        return [
            ActionGroup::make([
                Action::make('last_7_days')
                    ->label(__('admin.last_7_days'))
                    ->color($this->filter === 'last_7_days' ? 'primary' : 'gray')
                    ->action(fn () => $this->setFilter('last_7_days')),
                Action::make('last_30_days')
                    ->label(__('admin.last_30_days'))
                    ->color($this->filter === 'last_30_days' ? 'primary' : 'gray')
                    ->action(fn () => $this->setFilter('last_30_days')),
                Action::make('last_90_days')
                    ->label(__('admin.last_90_days'))
                    ->color($this->filter === 'last_90_days' ? 'primary' : 'gray')
                    ->action(fn () => $this->setFilter('last_90_days')),
                Action::make('this_month')
                    ->label(__('admin.this_month'))
                    ->color($this->filter === 'this_month' ? 'primary' : 'gray')
                    ->action(fn () => $this->setFilter('this_month')),
                Action::make('last_month')
                    ->label(__('admin.last_month'))
                    ->color($this->filter === 'last_month' ? 'primary' : 'gray')
                    ->action(fn () => $this->setFilter('last_month')),
            ])
                ->label(__('admin.filter_period'))
                ->icon('heroicon-o-calendar-days'),

            ActionGroup::make(array_map(fn ($year) => Action::make("year_{$year}")
                ->label($year)
                ->color(($this->filter === 'this_year' && $this->year == $year) ? 'primary' : 'gray')
                ->action(fn () => $this->setFilter('this_year', $year)),
                $years))
                ->label(__('admin.year'))
                ->icon('heroicon-o-calendar')
                ->visible(! empty($years)),

            Action::make('custom_range')
                ->label(__('admin.custom_range'))
                ->icon('heroicon-o-calendar')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('start_date')
                        ->label(__('admin.start_date'))
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('end_date')
                        ->label(__('admin.end_date'))
                        ->required()
                        ->afterOrEqual('start_date'),
                ])
                ->action(function (array $data) {
                    $this->startDate = $data['start_date'];
                    $this->endDate = $data['end_date'];
                    $this->filter = 'custom';
                    $this->updateChartData();
                }),
        ];
    }

    public function setFilter(string $filter, ?string $year = null): void
    {
        $this->filter = $filter;
        $this->year = $year;
        $this->startDate = null;
        $this->endDate = null;
        $this->updateChartData();
    }

    protected function getData(): array
    {
        $query = Order::where('status', 'completed');
        $startDate = null;
        $endDate = now();

        switch ($this->filter) {
            case 'last_7_days':
                $startDate = now()->subDays(7);
                break;
            case 'last_30_days':
                $startDate = now()->subDays(30);
                break;
            case 'last_90_days':
                $startDate = now()->subDays(90);
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $year = $this->year ?? now()->year;
                $startDate = now()->setYear($year)->startOfYear();
                $endDate = now()->setYear($year)->endOfYear();
                break;
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    $startDate = \Carbon\Carbon::parse($this->startDate)->startOfDay();
                    $endDate = \Carbon\Carbon::parse($this->endDate)->endOfDay();
                }
                break;
        }

        if ($startDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Get revenue data
        $revenueData = (clone $query)->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as revenue')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get order count data
        $orderCountData = (clone $query)->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as order_count')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Generate date labels and values
        $labels = [];
        $revenueValues = [];
        $orderCountValues = [];

        if ($startDate && $endDate) {
            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $labels[] = $date->format('M d');

                $revenueRecord = $revenueData->firstWhere('date', $dateStr);
                $revenueValues[] = $revenueRecord ? (float) $revenueRecord->revenue : 0;

                $orderCountRecord = $orderCountData->firstWhere('date', $dateStr);
                $orderCountValues[] = $orderCountRecord ? (int) $orderCountRecord->order_count : 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => __('admin.revenue'),
                    'data' => $revenueValues,
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => __('admin.orders'),
                    'data' => $orderCountValues,
                    'borderColor' => '#FF6384',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
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
                    'mode' => 'index',
                    'intersect' => false,
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": ";
                            }
                            if (context.datasetIndex === 0) {
                                label += "Rp " + new Intl.NumberFormat("id-ID").format(context.parsed.y);
                            } else {
                                label += context.parsed.y + " orders";
                            }
                            return label;
                        }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => __('admin.revenue'),
                    ],
                    'ticks' => [
                        'callback' => 'function(value) {
                            return "Rp " + new Intl.NumberFormat("id-ID", {
                                notation: "compact",
                                compactDisplay: "short"
                            }).format(value);
                        }',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => __('admin.orders'),
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
