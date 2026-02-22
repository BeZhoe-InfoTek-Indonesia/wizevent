<?php

namespace App\Filament\Resources\Performers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PerformersTable
{
    public static function configure(Table $table): Table
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
                TextColumn::make('phone')
                    ->label(__('performer.phone'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('type.name')
                    ->label(__('performer.type'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('profession.name')
                    ->label(__('performer.profession'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label(__('performer.is_active'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('events_count')
                    ->label(__('performer.events_count'))
                    ->counts('events')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('performer.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modal(),
                EditAction::make()
                    ->modal(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
