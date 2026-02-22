<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SettingComponentForm
{
    public static function configure(Schema $schema, ?\Illuminate\Database\Eloquent\Model $parent = null): Schema
    {
        $isTermsAndConditions = $parent?->key === 'terms_&_condition';

        $options = [
            'string' => __('setting.type_string'),
            'integer' => __('setting.type_integer'),
            'boolean' => __('setting.type_boolean'),
        ];

        if ($isTermsAndConditions) {
            $options['html'] = __('setting.type_html');
        }

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
                    ->options($options)
                    ->default($isTermsAndConditions ? 'html' : 'string')
                    ->live()
                    ->placeholder(__('setting.component_type_placeholder'))
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Reset all value fields when type changes
                        $set('value_text', null);
                        $set('value_boolean', false);
                        $set('value_html', null);
                        
                        if ($state === 'boolean') {
                            $set('value_boolean', true);
                        } elseif ($state === 'integer') {
                            $set('value_text', '0');
                        }
                    }),

                // Text Input for String/Integer
                TextInput::make('value_text')
                    ->label(__('setting.component_value'))
                    ->required()
                    ->maxLength(65535)
                    ->placeholder(fn (callable $get): string => match ($get('type')) {
                        'integer' => __('setting.component_value_placeholder_integer'),
                        'string' => __('setting.component_value_placeholder_string'),
                        default => __('setting.component_value_placeholder_default'),
                    })
                    ->visible(fn (callable $get): bool => in_array($get('type'), ['string', 'integer']))
                    ->afterStateHydrated(function (TextInput $component, $state, $record) {
                        if ($record && in_array($record->type, ['string', 'integer'])) {
                            $component->state($record->value);
                        }
                    })
                    ->dehydrated(fn (callable $get) => in_array($get('type'), ['string', 'integer']))
                    ->rules(['string']),

                // Toggle for Boolean
                Toggle::make('value_boolean')
                    ->label(__('setting.component_value'))
                    ->required()
                    ->visible(fn (callable $get): bool => $get('type') === 'boolean')
                    ->afterStateHydrated(function (Toggle $component, $state, $record) {
                        if ($record && $record->type === 'boolean') {
                            $component->state($record->value === 'true');
                        }
                    })
                    ->dehydrated(fn (callable $get) => $get('type') === 'boolean')
                    ->onColor('success')
                    ->offColor('danger'),

                // Rich Editor for HTML
                \Filament\Forms\Components\RichEditor::make('value_html')
                    ->label(__('setting.component_value'))
                    ->required()
                    ->visible(fn (callable $get): bool => $get('type') === 'html')
                    ->afterStateHydrated(function (\Filament\Forms\Components\RichEditor $component, $state, $record) {
                        if ($record && $record->type === 'html') {
                            $component->state($record->value);
                        }
                    })
                    ->dehydrated(fn (callable $get) => $get('type') === 'html')
                    ->columnSpanFull(),

                // Hidden field to map data back to 'value'
                \Filament\Forms\Components\Hidden::make('value')
                    ->dehydrated(true)
                    ->default(null),
            ]);
    }
}
