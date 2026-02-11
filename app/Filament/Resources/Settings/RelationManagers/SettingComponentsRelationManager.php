<?php

namespace App\Filament\Resources\Settings\RelationManagers;

use App\Filament\Resources\Settings\Schemas\SettingComponentForm;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SettingComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    protected static ?string $title = 'Setting Components';

    protected static UnitEnum|string|null $navigationLabel = 'Components';

    public function form(Schema $schema): Schema
    {
        return SettingComponentForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('setting.component_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('setting.component_type'))
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'string' => 'gray',
                        'integer' => 'blue',
                        'boolean' => 'green',
                    }),
                TextColumn::make('value')
                    ->label(__('setting.component_value'))
                    ->searchable()
                    ->limit(50),
            ])
            ->recordActions([
                EditAction::make()
                    ->modal(),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->modal(),
            ]);
    }
}
