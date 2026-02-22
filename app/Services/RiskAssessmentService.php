<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EventPlan;
use Illuminate\Support\Facades\Log;

class RiskAssessmentService
{
    public function __construct(private AiService $aiService) {}

    /**
     * Assess risks for the given event plan.
     *
     * @param EventPlan $plan
     * @return array<string, mixed>|null
     */
    public function assess(EventPlan $plan): ?array
    {
        $result = $this->aiService->assessRisks([
            'event_category'       => $plan->event_category,
            'target_audience_size' => $plan->target_audience_size,
            'location'             => $plan->location,
            'event_date'           => $plan->event_date?->format('Y-m-d'),
            'budget_target'        => (float) ($plan->budget_target ?? 0),
            'revenue_target'       => (float) ($plan->revenue_target ?? 0),
        ]);

        if ($result && empty($result['errors'])) {
            $plan->update(['ai_risk_result' => $result]);
        }

        return $result;
    }

    /**
     * Compute a color-coded badge color based on overall rating.
     *
     * @param string $rating
     * @return string Tailwind/Filament color name
     */
    public function ratingColor(string $rating): string
    {
        return match (strtolower($rating)) {
            'low'      => 'success',
            'medium'   => 'warning',
            'high'     => 'danger',
            'critical' => 'danger',
            default    => 'gray',
        };
    }

    /**
     * Compute severity color for a risk dimension.
     *
     * @param string $severity
     * @return string Tailwind/Filament color name
     */
    public function severityColor(string $severity): string
    {
        return match (strtolower($severity)) {
            'low'      => 'success',
            'medium'   => 'warning',
            'high'     => 'danger',
            'critical' => 'danger',
            default    => 'gray',
        };
    }

    /**
     * Compute the overall risk score percentage (0-100).
     *
     * @param array<string, mixed> $result
     * @return int
     */
    public function overallScore(array $result): int
    {
        return (int) ($result['overall_score'] ?? 0);
    }
}
