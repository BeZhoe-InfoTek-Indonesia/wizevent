<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SettingComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('setting.component_name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('setting.component_name_placeholder')),
                Select::make('type')
                    ->label(__('setting.component_type'))
                    ->required()
                    ->options([
                        'string' => __('setting.type_string'),
                        'integer' => __('setting.type_integer'),
                        'boolean' => __('setting.type_boolean'),
                    ])
                    ->default('string')
                    ->live()
                    ->placeholder(__('setting.component_type_placeholder'))
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'boolean') {
                            $set('value', 'true');
                        } elseif ($state === 'integer') {
                            $set('value', '0');
                        } else {
                            $set('value', '');
                        }
                    }),
                TextInput::make('value')
                    ->label(__('setting.component_value'))
                    ->required()
                    ->maxLength(65535)
                    ->placeholder(fn (callable $get): string => match ($get('type')) {
                        'integer' => __('setting.component_value_placeholder_integer'),
                        'string' => __('setting.component_value_placeholder_string'),
                        default => __('setting.component_value_placeholder_default'),
                    })
                    ->hidden(fn (callable $get): bool => $get('type') === 'boolean')
                    ->rules(function (callable $get) {
                        $type = $get('type');

                        return match ($type) {
                            'integer' => ['numeric'],
                            'boolean' => ['in:true,false'],
                            'string' => ['string'],
                            default => [],
                        };
                    }),
                Toggle::make('value')
                    ->label(__('setting.component_value'))
                    ->required()
                    ->hidden(fn (callable $get): bool => $get('type') !== 'boolean')
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }
}
