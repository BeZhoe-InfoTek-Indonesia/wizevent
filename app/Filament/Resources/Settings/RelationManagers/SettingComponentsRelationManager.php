<?php

namespace App\Filament\Resources\Settings\RelationManagers;

use App\Filament\Resources\Settings\Schemas\SettingComponentForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
        return SettingComponentForm::configure($schema, $this->getOwnerRecord());
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
                        'html' => 'yellow',
                        default => 'gray',
                    }),
                TextColumn::make('value')
                    ->label(__('setting.component_value'))
                    ->searchable()
                    ->limit(50),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(true)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['value'] = match ($data['type']) {
                            'boolean' => $data['value_boolean'] ? 'true' : 'false',
                            'html' => $data['value_html'],
                            default => $data['value_text'] ?? null,
                        };

                        unset($data['value_boolean'], $data['value_html'], $data['value_text']);

                        return $data;
                    })
                    ->modal(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['value'] = match ($data['type']) {
                            'boolean' => $data['value_boolean'] ? 'true' : 'false',
                            'html' => $data['value_html'],
                            default => $data['value_text'] ?? null,
                        };

                        unset($data['value_boolean'], $data['value_html'], $data['value_text']);

                        return $data;
                    })
                    ->modal(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
