<?php

namespace App\Filament\Resources\Organizers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrganizerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('organizer.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('organizer.name_placeholder')),
                Textarea::make('description')
                    ->label(__('organizer.description'))
                    ->rows(3)
                    ->placeholder(__('organizer.description_placeholder')),
                TextInput::make('email')
                    ->label(__('organizer.email'))
                    ->email()
                    ->placeholder(__('organizer.email_placeholder')),
                TextInput::make('phone')
                    ->label(__('organizer.phone'))
                    ->tel()
                    ->placeholder(__('organizer.phone_placeholder')),
                TextInput::make('website')
                    ->label(__('organizer.website'))
                    ->url()
                    ->placeholder(__('organizer.website_placeholder')),
                Textarea::make('social_media')
                    ->label(__('organizer.social_media'))
                    ->rows(2)
                    ->helperText(__('organizer.social_media_help'))
                    ->placeholder(__('organizer.social_media_placeholder')),
                Textarea::make('address')
                    ->label(__('organizer.address'))
                    ->rows(3)
                    ->placeholder(__('organizer.address_placeholder')),
                FileUpload::make('logo')
                    ->label(__('organizer.logo'))
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('organizers/logos')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText(__('organizer.logo_help')),
            ]);
    }
}
