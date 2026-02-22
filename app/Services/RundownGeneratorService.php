<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EventPlan;
use App\Models\EventPlanRundown;
use Illuminate\Support\Facades\Log;

/**
 * Service for generating and applying AI-powered event rundowns.
 *
 * Validates the event plan has required fields, delegates to AiService
 * for agenda generation, then creates EventPlanRundown records from
 * the AI output, appending to any existing items.
 */
class RundownGeneratorService
{
    public function __construct(private AiService $aiService) {}

    /**
     * Validate that the plan has enough data to generate a rundown.
     *
     * @param  EventPlan $plan
     * @return array<string> Validation errors (empty = valid)
     */
    public function validate(EventPlan $plan): array
    {
        $errors = [];

        if (empty($plan->event_category)) {
            $errors[] = __('event_plan_rundown.validation.missing_category');
        }

        if (empty($plan->target_audience_size)) {
            $errors[] = __('event_plan_rundown.validation.missing_audience_size');
        }

        return $errors;
    }

    /**
     * Generate an AI-suggested rundown for the given plan.
     *
     * Returns the raw AI items array (or null on failure).
     *
     * @param  EventPlan             $plan
     * @return array<int, array<string, mixed>>|null
     */
    public function generate(EventPlan $plan): ?array
    {
        $errors = $this->validate($plan);
        if (!empty($errors)) {
            return null;
        }

        // Build talent list for the prompt
        $talents = $plan->talents()
            ->with('performer')
            ->get()
            ->map(fn ($t) => [
                'name'     => $t->performer?->name ?? 'Unknown',
                'duration' => $t->slot_duration_minutes,
            ])
            ->toArray();

        $result = $this->aiService->generateRundown([
            'event_category'       => $plan->event_category,
            'target_audience_size' => $plan->target_audience_size,
            'location'             => $plan->location ?? '',
            'talents'              => $talents,
        ]);

        return $result['items'] ?? null;
    }

    /**
     * Apply AI-generated rundown items to the plan, appending to existing items.
     *
     * Continues sort_order from the current maximum so existing items are
     * never displaced.
     *
     * @param  EventPlan                          $plan
     * @param  array<int, array<string, mixed>>   $items  Items from AiService::generateRundown()
     * @return int Number of rundown items created
     */
    public function apply(EventPlan $plan, array $items): int
    {
        $nextOrder = (int) EventPlanRundown::where('event_plan_id', $plan->id)->max('sort_order') + 1;
        $created = 0;

        foreach ($items as $item) {
            $title = $item['title'] ?? null;
            if (empty($title)) {
                continue;
            }

            EventPlanRundown::create([
                'event_plan_id' => $plan->id,
                'title'         => $title,
                'description'   => $item['description'] ?? null,
                'start_time'    => $item['start_time'] ?? null,
                'end_time'      => $item['end_time'] ?? null,
                'type'          => $item['type'] ?? 'other',
                'notes'         => $item['notes'] ?? null,
                'sort_order'    => $nextOrder++,
            ]);

            $created++;
        }

        Log::info("RundownGeneratorService: Created {$created} rundown items for plan #{$plan->id}");

        return $created;
    }
}
