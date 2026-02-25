<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventPlanResource\Widgets;

use App\Models\EventPlan;
use App\Models\EventPlanRundown;
use Filament\Widgets\Widget;

/**
 * Renders a vertical timeline of rundown items, colour-coded by type.
 */
class RundownTimelineWidget extends Widget
{
    public ?EventPlan $record = null;

    protected string $view = 'filament.widgets.rundown-timeline';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        if (! $this->record) {
            return ['items' => [], 'empty' => true];
        }

        $items = EventPlanRundown::where('event_plan_id', $this->record->id)
            ->with('talent.performer')
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get()
            ->map(fn (EventPlanRundown $r): array => [
                'title' => $r->title,
                'start_time' => $r->start_time ? substr((string) $r->start_time, 0, 5) : '—',
                'end_time' => $r->end_time ? substr((string) $r->end_time, 0, 5) : '—',
                'duration' => $r->duration_minutes,
                'type' => $r->type,
                'type_color' => $r->type_color,
                'talent_name' => $r->talent?->performer?->name,
                'description' => $r->description,
            ])
            ->toArray();

        return [
            'items' => $items,
            'empty' => empty($items),
            'empty_label' => __('event_plan_rundown.no_rundown'),
            'heading' => __('event_plan_rundown.timeline_heading'),
        ];
    }
}
