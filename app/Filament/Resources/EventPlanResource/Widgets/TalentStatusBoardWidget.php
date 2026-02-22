<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventPlanResource\Widgets;

use App\Models\EventPlan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Displays a kanban-style board showing talent counts by contract status
 * with fee summaries per column.
 */
class TalentStatusBoardWidget extends StatsOverviewWidget
{
    public ?EventPlan $record = null;

    protected function getStats(): array
    {
        if (! $this->record) {
            return [];
        }

        $plan = $this->record;
        $talents = $plan->talents()->with('performer')->get();

        if ($talents->isEmpty()) {
            return [
                Stat::make(__('event_plan_talent.status_board.no_talent'), '0')
                    ->description(__('event_plan_talent.status_board.no_talent_hint'))
                    ->color('gray'),
            ];
        }

        $statuses = ['draft', 'negotiating', 'confirmed', 'cancelled'];

        return collect($statuses)->map(function (string $status) use ($talents): Stat {
            $group   = $talents->where('contract_status', $status);
            $count   = $group->count();
            $feeSum  = $group->sum('planned_fee');

            $names = $group->take(3)->map(fn ($t) => $t->performer?->name ?? '?')->implode(', ');
            $description = $count > 0
                ? $names . ($count > 3 ? ' +' . ($count - 3) . ' more' : '')
                : __('event_plan_talent.status_board.none');

            $color = match($status) {
                'confirmed'  => 'success',
                'negotiating' => 'warning',
                'cancelled'  => 'danger',
                default      => 'gray',
            };

            return Stat::make(
                __("event_plan_talent.contract_statuses.{$status}"),
                $count
            )
                ->description($description . ($feeSum > 0 ? ' · Rp ' . number_format((float) $feeSum, 0, ',', '.') : ''))
                ->color($color);
        })->toArray();
    }
}
