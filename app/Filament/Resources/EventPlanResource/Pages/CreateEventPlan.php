<?php

namespace App\Filament\Resources\EventPlanResource\Pages;

use App\Filament\Resources\EventPlanResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Create page for EventPlan.
 *
 * Handles creation of new event planning records.
 * Automatically captures the authenticated user ID for audit trail.
 * Processes currency fields from form input.
 *
 * Only Super Admin users can access this resource (per AGENTS.md permission structure).
 */
class CreateEventPlan extends CreateRecord
{
    protected static string $resource = EventPlanResource::class;
    
    /**
     * Execute the mutateFormDataBeforeCreate hook to process currency fields.
     *
     * Converts money fields from formatted strings (with thousand separators) to numeric values.
     * Captures the current authenticated user ID for created_by and updated_by audit fields.
     *
     * @param array<string, mixed> $data The form data to mutate
     * @return array<string, mixed> The mutated data with processed currency fields and user IDs
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if (isset($data['budget_target'])) {
            $data['budget_target'] = (float) str_replace('.', '', $data['budget_target']);
        }
        if (isset($data['revenue_target'])) {
            $data['revenue_target'] = (float) str_replace('.', '', $data['revenue_target']);
        }

        return $data;
    }

}
