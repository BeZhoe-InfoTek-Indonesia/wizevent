<?php

namespace App\Filament\Resources\EventPlanResource\Pages;

use App\Filament\Resources\EventPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * List page for EventPlan.
 *
 * Displays all event planning records in a sortable, filterable table.
 * Provides action to create new event plans.
 *
 * Only Super Admin users can access this resource (per AGENTS.md permission structure).
 */
class ListEventPlans extends ListRecords
{
    protected static string $resource = EventPlanResource::class;

    /**
     * Get header actions (create button).
     *
     * @return array<Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
