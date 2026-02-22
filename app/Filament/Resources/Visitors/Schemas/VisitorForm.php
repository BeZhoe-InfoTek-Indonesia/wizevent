<?php

namespace App\Filament\Resources\Visitors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class VisitorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('visitor.name'))
                    ->placeholder(__('visitor.name_placeholder'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label(__('visitor.email'))
                    ->placeholder(__('visitor.email_placeholder'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('identity_number')
                    ->label(__('visitor.identity_number'))
                    ->placeholder(__('visitor.identity_number_placeholder'))
                    ->maxLength(50),

                TextInput::make('mobile_phone_number')
                    ->label(__('visitor.mobile_phone_number'))
                    ->placeholder(__('visitor.mobile_phone_number_placeholder'))
                    ->tel()
                    ->maxLength(20),

                Toggle::make('is_active')
                    ->label(__('visitor.is_active'))
                    ->default(true)
                    ->inline(false)
                    ->visible(fn () => auth()->user()?->hasRole('Super Admin')),

                TextInput::make('password')
                    ->label(__('visitor.password'))
                    ->placeholder(__('visitor.password_placeholder'))
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->maxLength(255),
            ]);
    }
}
