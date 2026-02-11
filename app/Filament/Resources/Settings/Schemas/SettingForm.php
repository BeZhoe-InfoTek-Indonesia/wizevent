<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('setting.key'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder(__('setting.key_placeholder')),
                TextInput::make('name')
                    ->label(__('setting.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('setting.name_placeholder')),
            ]);
    }
}
