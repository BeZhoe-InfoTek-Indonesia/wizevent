<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventPlanResource\Pages;

use App\Filament\Resources\EventPlanResource;
use App\Filament\Resources\EventPlanResource\Widgets\ExpenseByCategoryWidget;
use App\Filament\Resources\EventPlanResource\Widgets\PlanningVsRealizationWidget;
use App\Filament\Resources\EventPlanResource\Widgets\RevenueComparisonChartWidget;
use App\Filament\Resources\EventPlanResource\Widgets\RundownTimelineWidget;
use App\Filament\Resources\EventPlanResource\Widgets\SalesPhaseTrackerWidget;
use App\Filament\Resources\EventPlanResource\Widgets\TalentStatusBoardWidget;
use App\Models\EventPlan;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class ViewEventPlan extends ViewRecord
{
    protected static string $resource = EventPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('event-planner.title'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('title')
                                    ->label(__('event-planner.labels.title')),

                                \Filament\Infolists\Components\TextEntry::make('status')
                                    ->label(__('event-planner.labels.status'))
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'draft'     => 'gray',
                                        'active'    => 'success',
                                        'completed' => 'info',
                                        'archived'  => 'warning',
                                        default     => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => __("event-planner.statuses.{$state}")),

                                \Filament\Infolists\Components\TextEntry::make('event.title')
                                    ->label(__('event-planner.labels.event'))
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('event_date')
                                    ->label(__('event-planner.labels.event_date'))
                                    ->date()
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('event_category')
                                    ->label(__('event-planner.labels.event_category'))
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('target_audience_size')
                                    ->label(__('event-planner.labels.target_audience_size'))
                                    ->numeric()
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('target_audience_description')
                                    ->label(__('event-planner.labels.target_audience_description'))
                                    ->columnSpanFull()
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('budget_target')
                                    ->label(__('event-planner.labels.budget_target'))
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('revenue_target')
                                    ->label(__('event-planner.labels.revenue_target'))
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('location')
                                    ->label(__('event-planner.labels.location'))
                                    ->columnSpanFull()
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('description')
                                    ->label(__('event-planner.labels.description'))
                                    ->markdown()
                                    ->columnSpanFull()
                                    ->default('—'),

                                \Filament\Infolists\Components\TextEntry::make('notes')
                                    ->label(__('event-planner.labels.notes'))
                                    ->markdown()
                                    ->columnSpanFull()
                                    ->default('—'),
                            ]),
                    ]),
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Row 1: KPI Stats (full width — 7 stats)
            PlanningVsRealizationWidget::make(['record' => $this->record]),

            // Row 2: Revenue vs Expense charts
            RevenueComparisonChartWidget::make(['record' => $this->record]),
            ExpenseByCategoryWidget::make(['record' => $this->record]),

            // Row 3: Sales Phase Tracker (full width)
            SalesPhaseTrackerWidget::make(['record' => $this->record]),

            // Row 4: Talent Board (half) + Rundown Timeline (half)
            TalentStatusBoardWidget::make(['record' => $this->record]),
            RundownTimelineWidget::make(['record' => $this->record]),
        ];
    }

    /**
     * @return EventPlan
     */
    public function getRecord(): EventPlan
    {
        /** @var EventPlan $record */
        $record = parent::getRecord();
        return $record;
    }
}
