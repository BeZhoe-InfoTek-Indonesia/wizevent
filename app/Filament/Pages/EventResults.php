<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;

class EventResults extends Page
{
    protected static ?string $navigationLabel = 'DO_NOT_SHOW_ME';

    public static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-academic-cap';
    }

    public function getMaxWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function render(): View
    {
        $publishedEvents = Event::where('status', 'published')
            ->orderByDesc('event_date')
            ->get();

        return view('filament.pages.event-results', [
            'events' => $publishedEvents,
        ]);
    }
}
