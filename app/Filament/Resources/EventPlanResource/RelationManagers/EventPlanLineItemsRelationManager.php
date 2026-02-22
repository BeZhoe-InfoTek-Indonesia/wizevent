<?php

namespace App\Filament\Resources\EventPlanResource\RelationManagers;

use App\Models\EventPlanLineItem;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\CreateAction;


/**
 * Relation Manager for EventPlan line items (budget breakdown).
 *
 * Manages expense and revenue line items for event planning.
 * Supports creating, editing, and deleting budget line items with planned and actual amounts.
 */
class EventPlanLineItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'lineItems';

    protected static ?string $title = null;

    /**
     * Get the title for this relation manager.
     *
     * @param \Illuminate\Database\Eloquent\Model $ownerRecord
     * @param string $pageClass
     * @return string
     */
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('event-planner.line_items.title');
    }

    /**
     * Build form schema for creating/editing line items.
     *
     * @param Schema $form
     * @return Schema
     */
    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label(__('event-planner.line_items.labels.type'))
                    ->options([
                        'expense' => __('event-planner.line_items.types.expense'),
                        'revenue' => __('event-planner.line_items.types.revenue'),
                    ])
                    ->required()
                    ->default('expense')
                    ->reactive(),

                TextInput::make('category')
                    ->label(__('event-planner.line_items.labels.category'))
                    ->required()
                    ->placeholder(__('event-planner.line_items.placeholders.category'))
                    ->reactive(),

                Textarea::make('description')
                    ->label(__('event-planner.line_items.labels.description'))
                    ->rows(2)
                    ->placeholder(__('event-planner.line_items.placeholders.description')),

                TextInput::make('planned_amount')
                    ->label(__('event-planner.line_items.labels.planned_amount'))
                    ->prefix('Rp')
                    ->numeric()
                    ->required()
                    ->placeholder(__('event-planner.line_items.placeholders.planned_amount')),

                TextInput::make('actual_amount')
                    ->label(__('event-planner.line_items.labels.actual_amount'))
                    ->prefix('Rp')
                    ->numeric()
                    ->placeholder(__('event-planner.line_items.placeholders.actual_amount')),

                Textarea::make('notes')
                    ->label(__('event-planner.line_items.labels.notes'))
                    ->rows(2)
                    ->placeholder(__('event-planner.line_items.placeholders.notes')),

                TextInput::make('sort_order')
                    ->label(__('event-planner.line_items.labels.sort_order'))
                    ->numeric()
                    ->default(0),
            ])
            ->columns(2);
    }

    /**
     * Build table schema for displaying line items.
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label(__('event-planner.line_items.labels.type'))
                    ->badge()
                    ->color(fn (string $state): string => $state === 'expense' ? 'danger' : 'success')
                    ->formatStateUsing(fn (string $state): string => __("event-planner.line_items.types.{$state}"))
                    ->sortable(),

                TextColumn::make('category')
                    ->label(__('event-planner.line_items.labels.category'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label(__('event-planner.line_items.labels.description'))
                    ->searchable()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('planned_amount')
                    ->label(__('event-planner.line_items.labels.planned_amount'))
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('actual_amount')
                    ->label(__('event-planner.line_items.labels.actual_amount'))
                    ->money('IDR')
                    ->sortable()
                    ->default('-'),

                TextColumn::make('variance')
                    ->label(__('event-planner.line_items.labels.variance'))
                    ->money('IDR')
                    ->color(fn (EventPlanLineItem $record): string => $record->variance >= 0 ? 'success' : 'danger')
                    ->sortable(query: fn ($query) => $query->orderByRaw('planned_amount - COALESCE(actual_amount, 0)')),

                TextColumn::make('sort_order')
                    ->label(__('event-planner.line_items.labels.sort_order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);

    }
}
