<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizersRelationManager extends RelationManager
{
    protected static string $relationship = 'organizers';

    protected static ?string $title = 'Organizers';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('organizer.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('organizer.email'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label(__('organizer.phone'))
                    ->searchable()
                    ->toggleable(),
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
