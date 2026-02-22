<?php

namespace App\Filament\Resources\PaymentBanks\Pages;

use App\Filament\Resources\PaymentBanks\PaymentBankResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentBanks extends ListRecords
{
    protected static string $resource = PaymentBankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
