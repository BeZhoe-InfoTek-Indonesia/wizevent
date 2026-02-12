<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class ScanTickets extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected string $view = 'filament.pages.scan-tickets';
    /**
     * Get the navigation label for the page.
     * 
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('scanner.scanner_title');
    }

    /**
     * Get the title for the page.
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return __('scanner.scanner_title');
    }

    /**
     * Get the navigation group for the page.
     * 
     * @return string|null
     */
    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.operations');
    }

    /**
     * Determine if the page should be registered in the navigation.
     * 
     * @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['Super Admin', 'Event Manager', 'Check-in Staff', 'IT Supervisor']) 
            || auth()->user()->can('tickets.check-in');
    }

    public function mount(): void
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Event Manager', 'Check-in Staff', 'IT Supervisor']) 
            && !auth()->user()->can('tickets.check-in')) {
            abort(403);
        }
    }
}
