<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventPlanResource\Pages;
use App\Filament\Resources\EventPlanResource\RelationManagers;

use App\Models\EventPlan;
use App\Models\Event;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BackedEnum;

class EventPlanResource extends Resource
{
    protected static ?string $model = EventPlan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Event Management';

    protected static ?int $navigationSort = -1;

    public static function getLabel(): string
    {
        return __('event-planner.title');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->label(__('event-planner.labels.title'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('event-planner.placeholders.title')),

                        Select::make('event_id')
                            ->label(__('event-planner.labels.event'))
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder(__('event-planner.placeholders.event'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state) {
                                    $event = Event::find($state);
                                    if ($event) {
                                        $set('event_date', $event->event_date?->format('Y-m-d'));
                                        $set('location', $event->location);
                                        $set('description', $event->description);
                                    }
                                }
                            }),

                        TextInput::make('event_category')
                            ->label(__('event-planner.labels.event_category'))
                            ->maxLength(255)
                            ->placeholder(__('event-planner.placeholders.event_category')),

                        TextInput::make('target_audience_size')
                            ->label(__('event-planner.labels.target_audience_size'))
                            ->numeric()
                            ->stripCharacters('.')
                            ->required()
                            ->placeholder(__('event-planner.placeholders.target_audience_size')),

                        Textarea::make('target_audience_description')
                            ->label(__('event-planner.labels.target_audience_description'))
                            ->rows(3)
                            ->placeholder(__('event-planner.placeholders.target_audience_description')),

                        TextInput::make('budget_target')
                            ->label(__('event-planner.labels.budget_target'))
                            ->prefix('Rp')
                            ->numeric()
                            ->placeholder(__('event-planner.placeholders.budget_target')),

                        TextInput::make('revenue_target')
                            ->label(__('event-planner.labels.revenue_target'))
                            ->prefix('Rp')
                            ->numeric()
                            ->placeholder(__('event-planner.placeholders.revenue_target')),

                        DatePicker::make('event_date')
                            ->label(__('event-planner.labels.event_date'))
                            ->placeholder(__('event-planner.placeholders.event_date')),

                        TextInput::make('location')
                            ->label(__('event-planner.labels.location'))
                            ->maxLength(255)
                            ->placeholder(__('event-planner.placeholders.location')),

                        MarkdownEditor::make('description')
                            ->label(__('event-planner.labels.description'))
                            ->columnSpanFull()
                            ->placeholder(__('event-planner.placeholders.description')),

                        MarkdownEditor::make('notes')
                            ->label(__('event-planner.labels.notes'))
                            ->columnSpanFull()
                            ->placeholder(__('event-planner.placeholders.notes')),

                        Select::make('status')
                            ->label(__('event-planner.labels.status'))
                            ->options([
                                'draft' => __('event-planner.statuses.draft'),
                                'active' => __('event-planner.statuses.active'),
                                'completed' => __('event-planner.statuses.completed'),
                                'archived' => __('event-planner.statuses.archived'),
                            ])
                            ->default('draft')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make(__('event-planner.concept_narrative.section_title'))
                    ->schema([
                        Select::make('concept_status')
                            ->label(__('event-planner.labels.concept_status'))
                            ->options([
                                'brainstorm' => __('event-planner.concept_statuses.brainstorm'),
                                'drafted' => __('event-planner.concept_statuses.drafted'),
                                'finalized' => __('event-planner.concept_statuses.finalized'),
                                'synced' => __('event-planner.concept_statuses.synced'),
                            ])
                            ->default('brainstorm')
                            ->disabled(fn ($get) => $get('concept_status') === 'synced'),

                        TextInput::make('theme')
                            ->label(__('event-planner.labels.theme'))
                            ->placeholder(__('event-planner.placeholders.theme'))
                            ->maxLength(255),

                        TextInput::make('tagline')
                            ->label(__('event-planner.labels.tagline'))
                            ->placeholder(__('event-planner.placeholders.tagline'))
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('narrative_summary')
                            ->label(__('event-planner.labels.narrative_summary'))
                            ->placeholder(__('event-planner.placeholders.narrative_summary'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(fn ($record) => $record === null || empty($record->theme)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('event-planner.labels.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('event-planner.labels.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'completed' => 'info',
                        'archived' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __("event-planner.statuses.{$state}"))
                    ->sortable(),

                TextColumn::make('event.title')
                    ->label(__('event-planner.labels.event'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('budget_target')
                    ->label(__('event-planner.labels.budget_target'))
                    ->money('IDR', locale: 'id')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('revenue_target')
                    ->label(__('event-planner.labels.revenue_target'))
                     ->money('IDR', locale: 'id')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('event-planner.labels.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('event-planner.labels.status'))
                    ->options([
                        'draft' => __('event-planner.statuses.draft'),
                        'active' => __('event-planner.statuses.active'),
                        'completed' => __('event-planner.statuses.completed'),
                        'archived' => __('event-planner.statuses.archived'),
                    ]),

                Tables\Filters\Filter::make('has_linked_event')
                    ->label(__('event-planner.filters.has_linked_event'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('event_id'))
                    ->indicateUsing(function (array $data): array {
                        return ['has_linked_event' => __('event-planner.filters.has_linked_event')];
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EventPlanLineItemsRelationManager::class,
            RelationManagers\EventPlanTalentsRelationManager::class,
            RelationManagers\EventPlanRundownRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEventPlans::route('/'),
            'create' => Pages\CreateEventPlan::route('/create'),
            'view'   => Pages\ViewEventPlan::route('/{record}'),
            'edit'   => Pages\EditEventPlan::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'location'];
    }
}
