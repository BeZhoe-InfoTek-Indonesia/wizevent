<?php

namespace App\Filament\Resources\PaymentBanks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentBankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')
                    ->required(),
                TextInput::make('account_number')
                    ->required(),
                TextInput::make('account_holder')
                    ->required(),
                TextInput::make('qr_code_path'),
                TextInput::make('logo_path'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
