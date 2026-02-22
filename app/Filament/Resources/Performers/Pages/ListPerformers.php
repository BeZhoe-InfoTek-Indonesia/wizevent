<?php

namespace App\Filament\Resources\Performers\Pages;

use App\Filament\Resources\Performers\PerformerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPerformers extends ListRecords
{
    protected static string $resource = PerformerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modal(),
        ];
    }
}
