<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PerformersRelationManager extends RelationManager
{
    protected static string $relationship = 'performers';

    protected static ?string $title = 'Performers';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo.url')
                    ->label(__('performer.photo'))
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('performer.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type.name')
                    ->label(__('performer.type'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('profession.name')
                    ->label(__('performer.profession'))
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label(__('performer.is_active'))
                    ->boolean(),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect(),
            ]);
    }
}
