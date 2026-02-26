<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label(__('cms.category'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('question')
                    ->label(__('cms.question'))
                    ->placeholder(__('cms.question_placeholder'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('answer')
                    ->label(__('cms.answer'))
                    ->placeholder(__('cms.answer_placeholder'))
                    ->required()
                    ->columnSpanFull()
                    ->rows(4),

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
