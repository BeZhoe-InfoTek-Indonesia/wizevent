<?php

namespace App\Filament\Resources\EventPlanResource\RelationManagers;

use App\Models\EventPlanLineItem;
use App\Models\EventPlanTalent;
use App\Models\Performer;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Relation Manager for EventPlan talent assignments (performer lineup).
 *
 * Manages performer slots, fees, contract status, and budget correlation
 * within an event plan.
 */
class EventPlanTalentsRelationManager extends RelationManager
{
    protected static string $relationship = 'talents';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('event_plan_talent.title');
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('performer_id')
                    ->label(__('event_plan_talent.columns.performer'))
                    ->placeholder(__('event_plan_talent.placeholders.performer'))
                    ->options(
                        Performer::where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->rule(function ($get, $record) {
                        return function (string $attribute, $value, $fail) use ($get, $record) {
                            $planId = $this->getOwnerRecord()->id;
                            $existing = EventPlanTalent::where('event_plan_id', $planId)
                                ->where('performer_id', $value)
                                ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                ->exists();

                            if ($existing) {
                                $fail(__('event_plan_talent.messages.already_assigned'));
                            }
                        };
                    }),

                TextInput::make('planned_fee')
                    ->label(__('event_plan_talent.columns.planned_fee'))
                    ->placeholder(__('event_plan_talent.placeholders.planned_fee'))
                    ->prefix('Rp')
                    ->numeric()
                    ->minValue(0),

                TextInput::make('actual_fee')
                    ->label(__('event_plan_talent.columns.actual_fee'))
                    ->placeholder(__('event_plan_talent.placeholders.actual_fee'))
                    ->prefix('Rp')
                    ->numeric()
                    ->minValue(0),

                TimePicker::make('slot_time')
                    ->label(__('event_plan_talent.columns.slot_time'))
                    ->placeholder(__('event_plan_talent.placeholders.slot_time'))
                    ->seconds(false),

                TextInput::make('slot_duration_minutes')
                    ->label(__('event_plan_talent.columns.duration'))
                    ->placeholder(__('event_plan_talent.placeholders.duration'))
                    ->numeric()
                    ->minValue(1)
                    ->suffix('min'),

                TextInput::make('performance_order')
                    ->label(__('event_plan_talent.columns.performance_order'))
                    ->placeholder(__('event_plan_talent.placeholders.performance_order'))
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Select::make('contract_status')
                    ->label(__('event_plan_talent.columns.contract_status'))
                    ->placeholder(__('event_plan_talent.placeholders.contract_status'))
                    ->options([
                        'draft' => __('event_plan_talent.contract_statuses.draft'),
                        'negotiating' => __('event_plan_talent.contract_statuses.negotiating'),
                        'confirmed' => __('event_plan_talent.contract_statuses.confirmed'),
                        'cancelled' => __('event_plan_talent.contract_statuses.cancelled'),
                    ])
                    ->default('draft')
                    ->required(),

                Textarea::make('rider_notes')
                    ->label(__('event_plan_talent.columns.rider_notes'))
                    ->placeholder(__('event_plan_talent.placeholders.rider_notes'))
                    ->rows(3),

                Textarea::make('notes')
                    ->label(__('event_plan_talent.columns.notes'))
                    ->placeholder(__('event_plan_talent.placeholders.notes'))
                    ->rows(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('performer.name')
                    ->label(__('event_plan_talent.columns.performer'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('planned_fee')
                    ->label(__('event_plan_talent.columns.planned_fee'))
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format((float) $state, 0, ',', '.') : '—')
                    ->sortable(),

                TextColumn::make('actual_fee')
                    ->label(__('event_plan_talent.columns.actual_fee'))
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format((float) $state, 0, ',', '.') : '—')
                    ->sortable(),

                TextColumn::make('slot_time')
                    ->label(__('event_plan_talent.columns.slot_time'))
                    ->default('—'),

                TextColumn::make('slot_duration_minutes')
                    ->label(__('event_plan_talent.columns.duration'))
                    ->default('—')
                    ->suffix(' min'),

                TextColumn::make('contract_status')
                    ->label(__('event_plan_talent.columns.contract_status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => __("event_plan_talent.contract_statuses.{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'negotiating' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('budget_line_item_id')
                    ->label(__('event_plan_talent.columns.budget_linked'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->defaultSort('performance_order')
            ->headerActions([
                CreateAction::make()
                    ->label(__('event_plan_talent.actions.add'))
                    ->after(function (EventPlanTalent $record) {
                        $this->syncFeeToLineItem($record);
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->label(__('event_plan_talent.actions.edit'))
                    ->after(function (EventPlanTalent $record) {
                        $this->syncFeeToLineItem($record);
                    }),

                Action::make('link_to_budget')
                    ->label(__('event_plan_talent.actions.link_to_budget'))
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (EventPlanTalent $record): bool => $record->budget_line_item_id === null)
                    ->action(function (EventPlanTalent $record): void {
                        $lineItem = EventPlanLineItem::create([
                            'event_plan_id' => $record->event_plan_id,
                            'category' => 'Talent',
                            'description' => $record->performer->name ?? 'Talent',
                            'type' => 'expense',
                            'planned_amount' => $record->planned_fee ?? 0,
                            'actual_amount' => $record->actual_fee,
                            'sort_order' => 0,
                        ]);

                        $record->update(['budget_line_item_id' => $lineItem->id]);

                        Notification::make()
                            ->success()
                            ->title(__('event_plan_talent.messages.budget_linked'))
                            ->send();
                    }),

                Action::make('unlink_from_budget')
                    ->label(__('event_plan_talent.actions.unlink_from_budget'))
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (EventPlanTalent $record): bool => $record->budget_line_item_id !== null)
                    ->requiresConfirmation()
                    ->action(function (EventPlanTalent $record): void {
                        $record->update(['budget_line_item_id' => null]);

                        Notification::make()
                            ->warning()
                            ->title(__('event_plan_talent.messages.budget_unlinked'))
                            ->send();
                    }),

                DeleteAction::make()
                    ->label(__('event_plan_talent.actions.delete'))
                    ->after(function (EventPlanTalent $record): void {
                        // Nullify budget link before delete (handled by DB nullOnDelete, but log it)
                        Notification::make()
                            ->success()
                            ->title(__('event_plan_talent.messages.deleted'))
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('event_plan_talent.empty_state.heading'))
            ->emptyStateDescription(__('event_plan_talent.empty_state.description'));
    }

    /**
     * Sync talent fee to linked budget line item if one exists.
     */
    private function syncFeeToLineItem(EventPlanTalent $talent): void
    {
        if ($talent->budget_line_item_id) {
            $lineItem = EventPlanLineItem::find($talent->budget_line_item_id);
            if ($lineItem) {
                $lineItem->update([
                    'planned_amount' => $talent->planned_fee ?? $lineItem->planned_amount,
                    'actual_amount' => $talent->actual_fee,
                ]);
            }
        }
    }
}
