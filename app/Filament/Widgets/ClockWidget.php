<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ClockWidget extends Widget
{
    protected static ?int $sort = -1;

    protected string $view = 'filament.widgets.clock-widget';

    protected int|string|array $columnSpan = 6;
}
