<?php

namespace App\Filament\Resources\PaymentBanks;

use App\Filament\Resources\PaymentBanks\Pages\CreatePaymentBank;
use App\Filament\Resources\PaymentBanks\Pages\EditPaymentBank;
use App\Filament\Resources\PaymentBanks\Pages\ListPaymentBanks;
use App\Filament\Resources\PaymentBanks\Schemas\PaymentBankForm;
use App\Filament\Resources\PaymentBanks\Tables\PaymentBanksTable;
use App\Models\PaymentBank;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentBankResource extends Resource
{
    protected static ?string $model = PaymentBank::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'CMS';
    }

    public static function getModelLabel(): string
    {
        return __('cms.payment_bank');
    }

    public static function getPluralModelLabel(): string
    {
        return __('cms.payment_banks');
    }

    public static function form(Schema $schema): Schema
    {
        return PaymentBankForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentBanksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentBanks::route('/'),
            'create' => CreatePaymentBank::route('/create'),
            'edit' => EditPaymentBank::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
