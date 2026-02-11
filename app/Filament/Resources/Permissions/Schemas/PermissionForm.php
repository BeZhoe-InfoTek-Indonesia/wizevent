<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Permission Name')
                    ->placeholder(__('permission.placeholders.name'))
                    ->helperText(__('permission.helper_text.name')),

                TextInput::make('guard_name')
                    ->default('web')
                    ->required()
                    ->maxLength(255)
                    ->label('Guard Name')
                    ->disabled(),

                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->label('Assign to Roles')
                    ->placeholder(__('permission.placeholders.roles')),
            ]);
    }
}
