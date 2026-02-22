<?php

namespace App\Filament\Resources\PaymentBanks\Pages;

use App\Filament\Resources\PaymentBanks\PaymentBankResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentBank extends CreateRecord
{
    protected static string $resource = PaymentBankResource::class;
}
