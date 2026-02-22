<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventPlanResource\Widgets;

use App\Models\EventPlan;
use Filament\Widgets\ChartWidget;

class ExpenseByCategoryWidget extends ChartWidget
{
    /**
     * The EventPlan record for which the widget is displayed.
     *
     * @var EventPlan|null
     */
    public ?EventPlan $record = null;

    /**
     * Get the widget heading (title).
     *
     * @return string
     */
    public function getHeading(): string
    {
        return __('event-planner.planning_vs_realization.expense_by_category');
    }

    /**
     * Get the data for the expense by category chart.
     *
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        if (!$this->record) {
            return ['datasets' => [], 'labels' => []];
        }

        $plan = $this->record;

        // Eager load lineItems to avoid N+1 queries
        $expenseLineItems = $plan->lineItems()
            ->where('type', 'expense')
            ->orderBy('sort_order')
            ->get();

        if ($expenseLineItems->isEmpty()) {
            return ['datasets' => [], 'labels' => []];
        }

        $grouped = $expenseLineItems->groupBy('category');

        $labels = $grouped->keys()->toArray();
        $plannedData = $grouped->map(fn ($items) => (float) $items->sum('planned_amount'))->values()->toArray();
        $actualData = $grouped->map(fn ($items) => (float) $items->sum(fn ($i) => (float) ($i->actual_amount ?? 0)))->values()->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('event-planner.planning_vs_realization.planned_expenses'),
                    'data' => $plannedData,
                    'backgroundColor' => 'rgba(251, 146, 60, 0.6)',
                    'borderColor' => 'rgba(251, 146, 60, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => __('event-planner.planning_vs_realization.actual_expenses'),
                    'data' => $actualData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.6)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * Get the chart type.
     *
     * @return string
     */
    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * Get the chart options for the widget.
     *
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
            'scales' => [
                'x' => ['stacked' => false],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        // The currency prefix should be translated if needed in the future
                        'callback' => 'function(value) { return "Rp" + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
