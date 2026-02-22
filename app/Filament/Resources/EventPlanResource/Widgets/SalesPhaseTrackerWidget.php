<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventPlanResource\Widgets;

use App\Models\EventPlan;
use App\Models\OrderItem;
use App\Models\TicketType;
use Filament\Widgets\Widget;

/**
 * Displays a per-ticket-type progress table showing sold vs capacity,
 * fill rates, and sales phase status for the linked event.
 */
class SalesPhaseTrackerWidget extends Widget
{
    public ?EventPlan $record = null;

    protected string $view = 'filament.widgets.sales-phase-tracker';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        if (! $this->record || ! $this->record->event_id) {
            return [
                'rows'    => [],
                'empty'   => true,
                'heading' => __('event-planner.monitoring.sales_phase_tracker'),
                'no_event_label' => __('event-planner.planning_vs_realization.no_linked_event'),
            ];
        }

        $ticketTypes = TicketType::where('event_id', $this->record->event_id)->get();

        $rows = $ticketTypes->map(function (TicketType $tt): array {
            $sold = (int) OrderItem::whereHas('order', function ($q) {
                $q->where('status', 'completed');
            })
                ->where('ticket_type_id', $tt->id)
                ->sum('quantity');

            $capacity   = max(1, (int) $tt->quantity);
            $fillRate   = round(($sold / ($capacity + $sold)) * 100, 1);
            $remaining  = max(0, $capacity - $sold);

            $now = now();
            $salesStarted = $tt->sales_start_at && $now->gte($tt->sales_start_at);
            $salesEnded   = $tt->sales_end_at && $now->gt($tt->sales_end_at);

            $phase = match(true) {
                $salesEnded              => 'ended',
                $salesStarted            => 'live',
                $tt->sales_start_at !== null => 'upcoming',
                default                  => 'active',
            };

            return [
                'name'      => $tt->name,
                'price'     => number_format((float) $tt->price, 0, ',', '.'),
                'sold'      => $sold,
                'capacity'  => $capacity,
                'remaining' => $remaining,
                'fill_rate' => $fillRate,
                'phase'     => $phase,
            ];
        })->toArray();

        return [
            'rows'    => $rows,
            'empty'   => empty($rows),
            'heading' => __('event-planner.monitoring.sales_phase_tracker'),
            'no_event_label' => __('event-planner.planning_vs_realization.no_linked_event'),
        ];
    }
}
