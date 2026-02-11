<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;
use BackedEnum;
class ActivityResource extends Resource
{
    // ... model and properties ...
    protected static ?string $model = Activity::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-table-cells';

    protected static UnitEnum|string|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('log_name')
                    ->label('Log Name')
                    ->readOnly(),
                TextInput::make('description')
                    ->label('Description')
                    ->readOnly(),
                TextInput::make('subject_type')
                    ->label('Subject Type')
                    ->readOnly(),
                TextInput::make('subject_id')
                    ->label('Subject ID')
                    ->readOnly(),
                TextInput::make('causer.name')
                    ->label('Causer')
                    ->readOnly(),
                KeyValue::make('properties')
                    ->label('Properties')
                    ->readOnly(),
                DateTimePicker::make('created_at')
                    ->label('Created At')
                    ->readOnly(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('log_name'),
                TextEntry::make('description'),
                TextEntry::make('subject_type'),
                TextEntry::make('subject_id'),
                TextEntry::make('causer.name'),
                KeyValueEntry::make('properties'),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state, Activity $record) => $state ? class_basename($state) . ' #' . $record->subject_id : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Causer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->options(fn() => Activity::query()->pluck('log_name', 'log_name')->unique()->toArray()),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->options(fn() => Activity::query()->pluck('subject_type', 'subject_type')->unique()->filter()->mapWithKeys(fn($type) => [$type => class_basename($type)])->toArray()),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}

