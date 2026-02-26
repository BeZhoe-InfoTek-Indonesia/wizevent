<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;

class ViewEventResults extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $resource = EventResource::class;

    protected static ?string $title = 'Event Results & Analytics';

    public Event $record;

    public function getMaxWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record?->title ? "Results: {$this->record->title}" : 'Event Results';
    }

    protected string $view = 'filament.pages.view-event-results';

    public function getViewData(): array
    {
        return [
            'event' => $this->record,
        ];
    }
}
