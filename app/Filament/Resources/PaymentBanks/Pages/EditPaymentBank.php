<?php

namespace App\Filament\Resources\PaymentBanks\Pages;

use App\Filament\Resources\PaymentBanks\PaymentBankResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPaymentBank extends EditRecord
{
    protected static string $resource = PaymentBankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
