<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EventPlan;
use App\Models\EventPlanLineItem;
use Illuminate\Support\Facades\Log;

class BudgetForecastService
{
    public function __construct(private AiService $aiService) {}

    /**
     * Generate a budget forecast for the given event plan.
     *
     * @param EventPlan $plan
     * @return array<string, mixed>|null
     */
    public function forecast(EventPlan $plan): ?array
    {
        $errors = $this->validate($plan);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $result = $this->aiService->generateBudgetForecast([
            'event_category'        => $plan->event_category,
            'target_audience_size'  => $plan->target_audience_size,
            'location'              => $plan->location,
            'event_date'            => $plan->event_date?->format('Y-m-d'),
            'budget_target'         => (float) ($plan->budget_target ?? 0),
        ]);

        if ($result && empty($result['errors'])) {
            $plan->update(['ai_budget_result' => $result]);
        }

        return $result;
    }

    /**
     * Populate line items from a forecast result.
     *
     * @param EventPlan $plan
     * @param array<string, mixed> $forecast
     * @return int Number of line items created
     */
    public function populateLineItems(EventPlan $plan, array $forecast): int
    {
        $categories = $forecast['categories'] ?? [];
        if (empty($categories)) {
            return 0;
        }

        $created = 0;
        $maxSortOrder = (int) $plan->lineItems()->max('sort_order');

        foreach ($categories as $item) {
            $category = $item['category'] ?? 'Other';
            $amount = (float) ($item['estimated_amount'] ?? 0);
            $notes = $item['notes'] ?? null;

            EventPlanLineItem::create([
                'event_plan_id'  => $plan->id,
                'category'       => $category,
                'description'    => $notes,
                'type'           => 'expense',
                'planned_amount' => $amount,
                'actual_amount'  => null,
                'notes'          => __('event-planner.ai_actions.budget_forecast.ai_generated_note'),
                'sort_order'     => ++$maxSortOrder,
            ]);
            $created++;
        }

        Log::info("BudgetForecastService: Created {$created} line items for plan #{$plan->id}");

        return $created;
    }

    /**
     * Validate plan has required fields for forecasting.
     *
     * @param EventPlan $plan
     * @return array<string>
     */
    public function validate(EventPlan $plan): array
    {
        $errors = [];

        if (empty($plan->event_category)) {
            $errors[] = __('event-planner.ai_actions.budget_forecast.validation.missing_category');
        }

        if (empty($plan->target_audience_size)) {
            $errors[] = __('event-planner.ai_actions.budget_forecast.validation.missing_audience_size');
        }

        return $errors;
    }
}
