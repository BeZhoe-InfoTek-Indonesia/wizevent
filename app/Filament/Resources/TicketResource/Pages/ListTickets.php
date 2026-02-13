<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('scan')
                ->label('Scan Tickets')
                ->icon('heroicon-o-qr-code')
                ->color('primary')
                ->url(fn () => \App\Filament\Pages\ScanTickets::getUrl()),
        ];
    }
}
