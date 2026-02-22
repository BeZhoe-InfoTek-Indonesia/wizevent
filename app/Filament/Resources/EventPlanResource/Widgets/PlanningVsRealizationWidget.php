<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventPlanResource\Widgets;

use App\Models\EventPlan;
use App\Models\EventPlanRundown;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlanningVsRealizationWidget extends StatsOverviewWidget
{
    public ?EventPlan $record = null;

    protected function getStats(): array
    {
        if (!$this->record) {
            return [];
        }

        $plan = $this->record;

        // Planning totals
        $plannedRevenue  = $plan->total_planned_revenue;
        $plannedExpenses = $plan->total_planned_expenses;
        $plannedNetProfit = $plan->planned_net_profit;

        // Realization totals
        $actualRevenue   = $this->getActualRevenue($plan);
        $actualExpenses  = $plan->total_actual_expenses;
        $actualNetProfit = $actualRevenue - $actualExpenses;

        // KPI calculations
        $revenueAchievementRate = $plannedRevenue > 0
            ? round(($actualRevenue / $plannedRevenue) * 100, 1)
            : 0.0;

        $budgetUtilizationRate = $plannedExpenses > 0
            ? round(($actualExpenses / $plannedExpenses) * 100, 1)
            : 0.0;

        $netMargin = $actualRevenue > 0
            ? round((($actualRevenue - $actualExpenses) / $actualRevenue) * 100, 1)
            : 0.0;

        $targetAudience = (int) ($plan->target_audience_size ?? 0);
        $ticketsSold    = $this->getTicketsSold($plan);
        $ticketsVsTarget = $targetAudience > 0
            ? round(($ticketsSold / $targetAudience) * 100, 1)
            : 0.0;

        // ── New KPIs ─────────────────────────────────────
        // Talent Confirmation Rate
        $totalTalents     = $plan->talents()->count();
        $confirmedTalents = $plan->talents()->where('contract_status', 'confirmed')->count();
        $talentConfirmRate = $totalTalents > 0
            ? round(($confirmedTalents / $totalTalents) * 100, 1)
            : 0.0;

        // Rundown Completeness (has at least 3 items = "ready")
        $rundownCount = EventPlanRundown::where('event_plan_id', $plan->id)->count();
        $rundownReady = $rundownCount >= 3;

        // Days Until Event
        $daysUntil = null;
        $daysLabel = __('event-planner.monitoring.days_until_event');
        $daysValue = '—';
        $daysDescription = '';
        $daysColor = 'gray';

        if ($plan->event_date) {
            $daysUntil = (int) now()->diffInDays($plan->event_date, false);
            if ($daysUntil < 0) {
                $daysValue = __('event-planner.monitoring.past_event');
                $daysColor = 'gray';
            } elseif ($daysUntil === 0) {
                $daysValue = __('event-planner.monitoring.event_day');
                $daysColor = 'success';
            } else {
                $daysValue = __('event-planner.monitoring.days_left', ['days' => $daysUntil]);
                $daysColor = $daysUntil <= 7 ? 'danger' : ($daysUntil <= 30 ? 'warning' : 'info');
            }
            $daysDescription = $plan->event_date->format('d M Y');
        }

        return [
            // ── Row 1: Financial KPIs ─────────────────
            Stat::make(
                __('event-planner.planning_vs_realization.revenue_achievement_rate'),
                $revenueAchievementRate . '%'
            )
                ->description(__('event-planner.planning_vs_realization.actual_revenue') . ': Rp' . number_format($actualRevenue, 0, ',', '.'))
                ->color($revenueAchievementRate >= 100 ? 'success' : ($revenueAchievementRate >= 75 ? 'warning' : 'danger')),

            Stat::make(
                __('event-planner.planning_vs_realization.budget_utilization_rate'),
                $budgetUtilizationRate . '%'
            )
                ->description(__('event-planner.planning_vs_realization.actual_expenses') . ': Rp' . number_format($actualExpenses, 0, ',', '.'))
                ->color($budgetUtilizationRate <= 100 ? 'success' : 'danger'),

            Stat::make(
                __('event-planner.planning_vs_realization.net_margin'),
                $netMargin . '%'
            )
                ->description(__('event-planner.planning_vs_realization.actual_net_profit') . ': Rp' . number_format($actualNetProfit, 0, ',', '.'))
                ->color($actualNetProfit >= 0 ? 'success' : 'danger'),

            Stat::make(
                __('event-planner.planning_vs_realization.tickets_sold_vs_target'),
                $ticketsSold . ' / ' . $targetAudience
            )
                ->description($ticketsVsTarget . '% ' . __('event-planner.planning_vs_realization.tickets_sold'))
                ->color($ticketsVsTarget >= 100 ? 'success' : ($ticketsVsTarget >= 60 ? 'warning' : 'danger')),

            // ── Row 2: Operational KPIs ───────────────
            Stat::make(
                __('event-planner.monitoring.talent_confirmation_rate'),
                ($totalTalents > 0 ? $talentConfirmRate . '%' : '—')
            )
                ->description(__('event-planner.monitoring.confirmed_of', ['total' => $totalTalents])
                    . ' (' . $confirmedTalents . '/' . $totalTalents . ')'
                    . ($talentConfirmRate < 50 && $totalTalents > 0 ? ' ⚠ ' . __('event-planner.monitoring.unconfirmed_talent') : ''))
                ->color($totalTalents === 0 ? 'gray' : ($talentConfirmRate >= 80 ? 'success' : ($talentConfirmRate >= 50 ? 'warning' : 'danger'))),

            Stat::make(
                __('event-planner.monitoring.rundown_completeness'),
                $rundownCount > 0 ? $rundownCount : '0'
            )
                ->description($rundownReady
                    ? __('event-planner.monitoring.rundown_ready')
                    : __('event-planner.monitoring.items_in_rundown', ['count' => $rundownCount]))
                ->color($rundownReady ? 'success' : ($rundownCount > 0 ? 'warning' : 'gray')),

            Stat::make($daysLabel, $daysValue)
                ->description($daysDescription)
                ->color($daysColor),
        ];
    }

    protected function getActualRevenue(EventPlan $plan): float
    {
        if (!$plan->event_id) {
            return 0.0;
        }

        return (float) Order::where('event_id', $plan->event_id)
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    protected function getTicketsSold(EventPlan $plan): int
    {
        if (!$plan->event_id) {
            return 0;
        }

        return (int) OrderItem::whereHas('order', function ($q) use ($plan) {
            $q->where('event_id', $plan->event_id)->where('status', 'completed');
        })->sum('quantity');
    }
}

