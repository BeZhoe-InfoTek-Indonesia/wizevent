<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modal()
                ->using(function (array $data) {
                return DB::transaction(function () use ($data) {
                    return static::getModel()::create($data);
                });
            }),
        ];
    }
}
