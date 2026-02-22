<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventPlanResource\Widgets;

use App\Models\EventPlan;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueComparisonChartWidget extends ChartWidget
{
    public ?EventPlan $record = null;

    public function getHeading(): string
    {
        return __('event-planner.planning_vs_realization.revenue_comparison');
    }

    protected function getData(): array
    {
        if (!$this->record) {
            return ['datasets' => [], 'labels' => []];
        }

        $plan = $this->record;

        $plannedRevenue = $plan->total_planned_revenue;
        $revenueTarget = (float) ($plan->revenue_target ?? 0);
        $actualRevenue = $plan->event_id
            ? (float) Order::where('event_id', $plan->event_id)->where('status', 'completed')->sum('total_amount')
            : 0.0;

        return [
            'datasets' => [
                [
                    'label' => __('event-planner.planning_vs_realization.planned_revenue'),
                    'data' => [$plannedRevenue],
                    'backgroundColor' => 'rgba(20, 184, 166, 0.6)',
                    'borderColor' => 'rgba(20, 184, 166, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => __('event-planner.planning_vs_realization.actual_revenue'),
                    'data' => [$actualRevenue],
                    'backgroundColor' => 'rgba(139, 92, 246, 0.6)',
                    'borderColor' => 'rgba(139, 92, 246, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => __('event-planner.planning_vs_realization.revenue_target'),
                    'data' => [$revenueTarget],
                    'backgroundColor' => 'rgba(251, 191, 36, 0.6)',
                    'borderColor' => 'rgba(251, 191, 36, 1)',
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                ],
            ],
            'labels' => [__('event-planner.planning_vs_realization.revenue_comparison')],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp" + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
