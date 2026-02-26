<?php

namespace App\Filament\Resources\FaqCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('cms.category_name'))
                    ->placeholder(__('cms.category_name_placeholder'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label(__('cms.slug'))
                    ->placeholder(__('cms.slug_placeholder'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('order')
                    ->label(__('cms.order'))
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->step(1),

                Toggle::make('is_active')
                    ->label(__('cms.is_active'))
                    ->default(true)
                    ->required(),
            ]);
    }
}
