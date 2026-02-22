<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EventPlan;
use App\Models\TicketType;
use Illuminate\Support\Facades\Log;

class PricingStrategyService
{
    public function __construct(private AiService $aiService) {}

    /**
     * Generate a pricing strategy for the given event plan.
     *
     * Returns multi-scenario structure:
     * {
     *   "scenarios": {
     *     "pessimistic": { "label", "tiers": [], "total_projected_revenue", ... },
     *     "realistic":   { ... },
     *     "optimistic":  { ... }
     *   },
     *   "selected_scenario": "realistic"
     * }
     *
     * Falls back gracefully to legacy flat-tier format for backward compatibility.
     *
     * @param EventPlan $plan
     * @return array<string, mixed>|null
     */
    public function suggest(EventPlan $plan): ?array
    {
        $errors = $this->validate($plan);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $result = $this->aiService->suggestPricingStrategy([
            'event_category'       => $plan->event_category,
            'target_audience_size' => $plan->target_audience_size,
            'revenue_target'       => (float) ($plan->revenue_target ?? 0),
            'budget_target'        => (float) ($plan->budget_target ?? 0),
            'location'             => $plan->location,
        ]);

        if ($result && empty($result['errors'])) {
            // Normalise: wrap legacy flat-tier responses in multi-scenario structure
            if (! isset($result['scenarios']) && isset($result['tiers'])) {
                $result = [
                    'scenarios' => [
                        'realistic' => array_merge($result, ['label' => __('event-planner.pricing_scenarios.realistic')]),
                    ],
                    'selected_scenario' => 'realistic',
                ];
            }

            // Ensure a default selected_scenario is always present
            if (! isset($result['selected_scenario'])) {
                $result['selected_scenario'] = 'realistic';
            }

            $plan->update(['ai_pricing_result' => $result]);
        }

        return $result;
    }

    /**
     * Extract tiers for the currently selected scenario from a pricing result.
     *
     * Supports both new multi-scenario format and legacy flat-tier format.
     *
     * @param array<string, mixed> $pricingResult
     * @return array<int, array<string, mixed>>
     */
    public function getSelectedTiers(array $pricingResult): array
    {
        // New multi-scenario format
        if (isset($pricingResult['scenarios'])) {
            $selected = $pricingResult['selected_scenario'] ?? 'realistic';
            $scenario = $pricingResult['scenarios'][$selected] ?? reset($pricingResult['scenarios']);

            return is_array($scenario) ? ($scenario['tiers'] ?? []) : [];
        }

        // Legacy flat format
        return $pricingResult['tiers'] ?? [];
    }

    /**
     * Apply pricing tiers to the linked event as TicketType records.
     *
     * @param EventPlan $plan
     * @param array<int, array<string, mixed>> $tiers
     * @return int Number of ticket types created
     */
    public function applyToEvent(EventPlan $plan, array $tiers): int
    {
        if (!$plan->event_id) {
            return 0;
        }

        $created = 0;
        $eventDate = $plan->event?->event_date ?? now()->addDays(30);

        foreach ($tiers as $tier) {
            $name = $tier['name'] ?? 'General';
            $price = (float) ($tier['price'] ?? 0);
            $quantity = (int) ($tier['quantity'] ?? 100);
            $startDaysBefore = (int) ($tier['sales_start_days_before'] ?? 30);
            $endDaysBefore = (int) ($tier['sales_end_days_before'] ?? 0);

            $salesStart = $eventDate->copy()->subDays($startDaysBefore);
            $salesEnd = $endDaysBefore > 0
                ? $eventDate->copy()->subDays($endDaysBefore)
                : $eventDate->copy();

            TicketType::create([
                'event_id'       => $plan->event_id,
                'name'           => $name,
                'price'          => $price,
                'quantity'       => $quantity,
                'min_purchase'   => 1,
                'max_purchase'   => min(10, $quantity),
                'sales_start_at' => $salesStart,
                'sales_end_at'   => $salesEnd,
            ]);
            $created++;
        }

        Log::info("PricingStrategyService: Created {$created} ticket types for event #{$plan->event_id}");

        return $created;
    }

    /**
     * Validate plan has required fields for pricing strategy.
     *
     * @param EventPlan $plan
     * @return array<string>
     */
    public function validate(EventPlan $plan): array
    {
        $errors = [];

        if (empty($plan->revenue_target)) {
            $errors[] = __('event-planner.ai_actions.pricing_strategy.validation.missing_revenue_target');
        }

        if (empty($plan->target_audience_size)) {
            $errors[] = __('event-planner.ai_actions.pricing_strategy.validation.missing_audience_size');
        }

        return $errors;
    }
}
