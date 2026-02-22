<?php

namespace App\Filament\Resources\Visitors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class VisitorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('visitor.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('visitor.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('identity_number')
                    ->label(__('visitor.identity_number'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('mobile_phone_number')
                    ->label(__('visitor.mobile_phone_number'))
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label(__('visitor.is_active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                IconColumn::make('email_verified_at')
                    ->label(__('visitor.email_verified'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin.nav.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('admin.nav.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('verified')
                    ->label(__('visitor.verified'))
                    ->query(fn ($query) => $query->whereNotNull('email_verified_at')),
                Filter::make('unverified')
                    ->label(__('visitor.unverified'))
                    ->query(fn ($query) => $query->whereNull('email_verified_at')),
            ])
            ->recordActions([
                EditAction::make()
                    ->modal()
                    ->using(function (Model $record, array $data): Model {
                        return DB::transaction(function () use ($record, $data) {
                            $record->update($data);

                            return $record;
                        });
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
