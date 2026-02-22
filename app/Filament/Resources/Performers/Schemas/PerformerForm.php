<?php

namespace App\Filament\Resources\Performers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PerformerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('performer.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('performer.name_placeholder')),

                Textarea::make('description')
                    ->label(__('performer.description'))
                    ->rows(3)
                    ->placeholder(__('performer.description_placeholder')),

                TextInput::make('phone')
                    ->label(__('performer.phone'))
                    ->tel()
                    ->placeholder(__('performer.phone_placeholder')),

                FileUpload::make('photo')
                    ->label(__('performer.photo'))
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('performers/photos')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText(__('performer.photo_help')),

                Select::make('type')
                    ->label(__('performer.type'))
                    ->relationship(
                        'type',
                        'name',
                        fn (Builder $query) => $query->whereHas('setting', fn ($q) => $q->where('key', 'performer_types'))
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder(__('performer.type_placeholder'))
                    ->helperText(__('performer.type_help')),

                Select::make('profession')
                    ->label(__('performer.profession'))
                    ->relationship(
                        'profession',
                        'name',
                        fn (Builder $query) => $query->whereHas('setting', fn ($q) => $q->where('key', 'performer_professions'))
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder(__('performer.profession_placeholder'))
                    ->helperText(__('performer.profession_help')),

                Toggle::make('is_active')
                    ->label(__('performer.is_active'))
                    ->default(true),
            ]);
    }
}
