<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class ScanTickets extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected static string|\UnitEnum|null $navigationGroup = 'Operations';
    protected static ?string $navigationLabel = 'Scan Tickets';
    protected static ?string $title = 'Ticket Scanner';
    
    protected string $view = 'filament.pages.scan-tickets';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(['Super Admin', 'Event Manager', 'Check-in Staff']);
    }

    public function mount(): void
    {
        if (!auth()->user()->hasRole(['Super Admin', 'Event Manager', 'Check-in Staff'])) {
            abort(403);
        }
    }
}
