<?php

namespace App\Filament\Resources\EventPlanResource\RelationManagers;

use App\Models\EventPlanRundown;
use App\Models\EventPlanTalent;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Relation Manager for EventPlan rundown/agenda items.
 *
 * Manages time-blocked event agenda items linked to talent slots
 * within an event plan.
 */
class EventPlanRundownRelationManager extends RelationManager
{
    protected static string $relationship = 'rundowns';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('event_plan_rundown.title');
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('event_plan_rundown.columns.title'))
                    ->placeholder(__('event_plan_rundown.placeholders.title'))
                    ->required()
                    ->maxLength(255),

                TimePicker::make('start_time')
                    ->label(__('event_plan_rundown.columns.start_time'))
                    ->placeholder(__('event_plan_rundown.placeholders.start_time'))
                    ->required()
                    ->seconds(false),

                TimePicker::make('end_time')
                    ->label(__('event_plan_rundown.columns.end_time'))
                    ->placeholder(__('event_plan_rundown.placeholders.end_time'))
                    ->required()
                    ->seconds(false)
                    ->after('start_time'),

                Select::make('type')
                    ->label(__('event_plan_rundown.columns.type'))
                    ->placeholder(__('event_plan_rundown.placeholders.type'))
                    ->options([
                        'ceremony' => __('event_plan_rundown.types.ceremony'),
                        'performance' => __('event_plan_rundown.types.performance'),
                        'break' => __('event_plan_rundown.types.break'),
                        'setup' => __('event_plan_rundown.types.setup'),
                        'networking' => __('event_plan_rundown.types.networking'),
                        'registration' => __('event_plan_rundown.types.registration'),
                        'other' => __('event_plan_rundown.types.other'),
                    ])
                    ->default('other')
                    ->required(),

                Select::make('event_plan_talent_id')
                    ->label(__('event_plan_rundown.columns.talent'))
                    ->placeholder(__('event_plan_rundown.placeholders.talent'))
                    ->options(function (): array {
                        return EventPlanTalent::where('event_plan_id', $this->getOwnerRecord()->id)
                            ->with('performer')
                            ->get()
                            ->mapWithKeys(fn ($talent) => [$talent->id => $talent->performer?->name ?? "Talent #{$talent->id}"])
                            ->toArray();
                    })
                    ->nullable()
                    ->searchable(),

                TextInput::make('sort_order')
                    ->label(__('event_plan_rundown.columns.sort_order'))
                    ->placeholder(__('event_plan_rundown.placeholders.sort_order'))
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Textarea::make('description')
                    ->label(__('event_plan_rundown.columns.description'))
                    ->placeholder(__('event_plan_rundown.placeholders.description'))
                    ->rows(2),

                Textarea::make('notes')
                    ->label(__('event_plan_rundown.columns.notes'))
                    ->placeholder(__('event_plan_rundown.placeholders.notes'))
                    ->rows(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('sort_order')
                    ->label(__('event_plan_rundown.columns.sort_order'))
                    ->sortable()
                    ->width(50),

                TextColumn::make('title')
                    ->label(__('event_plan_rundown.columns.title'))
                    ->searchable()
                    ->wrap(),

                TextColumn::make('start_time')
                    ->label(__('event_plan_rundown.columns.time_range'))
                    ->formatStateUsing(function ($state, EventPlanRundown $record): string {
                        return $state . ' – ' . ($record->end_time ?? '?');
                    }),

                TextColumn::make('duration_minutes')
                    ->label(__('event_plan_rundown.columns.duration'))
                    ->state(function (EventPlanRundown $record): string {
                        return $record->duration_minutes . ' min';
                    }),

                TextColumn::make('type')
                    ->label(__('event_plan_rundown.columns.type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => __("event_plan_rundown.types.{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        'ceremony' => 'warning',
                        'performance' => 'purple',
                        'break' => 'gray',
                        'setup' => 'info',
                        'networking' => 'success',
                        'registration' => 'primary',
                        default => 'secondary',
                    }),

                TextColumn::make('talent.performer.name')
                    ->label(__('event_plan_rundown.columns.talent'))
                    ->default('—'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label(__('event_plan_rundown.actions.add'))
                    ->after(function (EventPlanRundown $record): void {
                        $this->checkTimeOverlap($record);
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->label(__('event_plan_rundown.actions.edit'))
                    ->after(function (EventPlanRundown $record): void {
                        $this->checkTimeOverlap($record);
                    }),

                DeleteAction::make()
                    ->label(__('event_plan_rundown.actions.delete')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('event_plan_rundown.empty_state.heading'))
            ->emptyStateDescription(__('event_plan_rundown.empty_state.description'));
    }

    /**
     * Check for time overlaps and show a warning notification.
     */
    private function checkTimeOverlap(EventPlanRundown $item): void
    {
        if (! $item->start_time || ! $item->end_time) {
            return;
        }

        $overlap = EventPlanRundown::where('event_plan_id', $item->event_plan_id)
            ->where('id', '!=', $item->id)
            ->where(function ($q) use ($item) {
                $q->whereBetween('start_time', [$item->start_time, $item->end_time])
                  ->orWhereBetween('end_time', [$item->start_time, $item->end_time]);
            })
            ->first();

        if ($overlap) {
            Notification::make()
                ->warning()
                ->title(__('event_plan_rundown.messages.time_overlap_warning', ['item' => $overlap->title]))
                ->send();
        }
    }
}
