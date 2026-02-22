<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('testimonial.information'))
                    ->schema([
                        Select::make('user_id')
                            ->label(__('testimonial.user'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder(__('testimonial.user_placeholder')),
                        Select::make('event_id')
                            ->label(__('testimonial.event'))
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder(__('testimonial.event_placeholder')),
                        TextInput::make('rating')
                            ->label(__('testimonial.rating'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required()
                            ->default(5)
                            ->suffix('/5')
                            ->placeholder(__('testimonial.rating_placeholder')),
                    ])
                    ->columns(1)
                    ->columnSpan(1),
                Section::make(__('testimonial.content_section'))
                    ->schema([
                        Textarea::make('content')
                            ->label(__('testimonial.content'))
                            ->required()
                            ->rows(5)
                            ->columnSpanFull()
                            ->placeholder(__('testimonial.content_placeholder')),
                    ])
                    ->columnSpan(1),
                Section::make(__('testimonial.moderation'))
                    ->schema([
                        Select::make('status')
                            ->label(__('testimonial.status'))
                            ->options([
                                'pending' => __('testimonial.pending'),
                                'approved' => __('testimonial.approved'),
                            ])
                            ->default('pending')
                            ->required()
                            ->placeholder(__('testimonial.status_placeholder')),
                        Toggle::make('is_published')
                            ->label(__('testimonial.published'))
                            ->default(false),
                        Toggle::make('is_featured')
                            ->label(__('testimonial.featured'))
                            ->default(false),
                    ])
                    ->columns(1)
                    ->columnSpan(1),
                Section::make(__('testimonial.image_section'))
                    ->schema([
                        FileUpload::make('image_path')
                            ->label(__('testimonial.image'))
                            ->image()
                            ->directory('testimonials')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull()
                            ->placeholder(__('testimonial.image_placeholder')),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($state): bool => empty($state)),
            ])
            ->columns(1);
    }
}
