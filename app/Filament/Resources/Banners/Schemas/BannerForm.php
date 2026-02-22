<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('cms.banner_title'))
                    ->placeholder(__('cms.banner_title_placeholder'))
                    ->required(),
                Select::make('type')
                    ->label(__('cms.banner_type'))
                    ->options([
                        'hero' => __('cms.banner_type_hero'),
                        'section' => __('cms.banner_type_section'),
                        'mobile' => __('cms.banner_type_mobile'),
                    ])
                    ->required(),
                FileUpload::make('banner_image')
                    ->label(__('cms.banner_image'))
                    ->hint(__('cms.banner_image_placeholder'))
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->required(fn ($record) => $record === null),
                TextInput::make('link_url')
                    ->label(__('cms.banner_link'))
                    ->placeholder(__('cms.banner_link_placeholder'))
                    ->url(),
                Select::make('link_target')
                    ->label(__('cms.banner_link_target'))
                    ->options([
                        '_self' => __('cms.banner_link_target_self'),
                        '_blank' => __('cms.banner_link_target_blank'),
                    ])
                    ->default('_self')
                    ->required(),
                TextInput::make('position')
                    ->label(__('cms.banner_position'))
                    ->placeholder(__('cms.banner_position_placeholder'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label(__('cms.banner_active'))
                    ->required(),
                DateTimePicker::make('start_date')
                    ->label(__('cms.banner_start_date')),
                DateTimePicker::make('end_date')
                    ->label(__('cms.banner_end_date')),
                TextInput::make('click_count')
                    ->label(__('cms.banner_click_count'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled(),
                TextInput::make('impression_count')
                    ->label(__('cms.banner_impression_count'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }
}
