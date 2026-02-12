<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickScanWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-scan-widget';
    
    protected int | string | array $columnSpan = 'compact';

    /**
     * Determine if the widget can be viewed by the current user.
     * 
     * @return bool
     */
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['Super Admin', 'Event Manager', 'Check-in Staff', 'IT Supervisor']) 
            || auth()->user()->can('tickets.check-in');
    }
}
