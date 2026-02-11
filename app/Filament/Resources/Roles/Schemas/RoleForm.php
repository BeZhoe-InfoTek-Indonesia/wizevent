<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->label('Role Name')
                    ->placeholder(__('role.placeholders.name')),

                TextInput::make('guard_name')
                    ->default('web')
                    ->required()
                    ->maxLength(255)
                    ->label('Guard Name')
                    ->disabled(),

                Select::make('permissions')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->searchable()
                    ->label('Assign Permissions')
                    ->placeholder(__('role.placeholders.permissions')),
            ]);
    }
}
