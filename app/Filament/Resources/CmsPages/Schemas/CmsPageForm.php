<?php

namespace App\Filament\Resources\CmsPages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CmsPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Basic Information')
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('cms.title'))
                                    ->placeholder(__('cms.title_placeholder'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(
                                        function (string $operation, $state, Set $set) {
                                            if ($operation === 'create') {
                                                $set('slug', Str::slug($state));
                                            }
                                        }
                                    ),

                                TextInput::make('slug')
                                    ->label(__('cms.slug'))
                                    ->placeholder(__('cms.slug_placeholder'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(\App\Models\CmsPage::class, 'slug', ignoreRecord: true),

                                Select::make('status')
                                    ->label(__('cms.status'))
                                    ->options([
                                        'draft' => __('cms.status_draft'),
                                        'published' => __('cms.status_published'),
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->live(),

                                Section::make(__('cms.content_blocks'))
                                    ->schema([
                                        Repeater::make('content')
                                            ->label('')
                                            ->schema([
                                                Select::make('type')
                                                    ->label('Block Type')
                                                    ->options([
                                                        'text' => __('cms.block_text'),
                                                        'html' => __('cms.block_html'),
                                                        'image' => __('cms.block_image'),
                                                        'video' => __('cms.block_video'),
                                                    ])
                                                    ->required(),

                                                Textarea::make('content')
                                                    ->label(__('cms.content'))
                                                    ->placeholder(__('cms.content_placeholder'))
                                                    ->rows(5),

                                                FileUpload::make('image_path')
                                                    ->label(__('cms.image'))
                                                    ->image()
                                                    ->directory('cms/images'),

                                                TextInput::make('alt_text')
                                                    ->label(__('cms.alt_text'))
                                                    ->placeholder(__('cms.alt_text_placeholder'))
                                                    ->maxLength(255),

                                                TextInput::make('url')
                                                    ->label(__('cms.video_url'))
                                                    ->placeholder(__('cms.video_url_placeholder'))
                                                    ->url(),

                                                TextInput::make('caption')
                                                    ->label(__('cms.caption'))
                                                    ->placeholder(__('cms.caption_placeholder'))
                                                    ->maxLength(255),
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['type'] ?? null)
                                            ->reorderableWithButtons(),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make(__('cms.publication'))
                            ->schema([
                                DateTimePicker::make('published_at')
                                    ->label(__('cms.published_at'))
                                    ->placeholder(__('cms.published_at_placeholder'))
                                    ->seconds(false)
                                    ->native(false),

                                TextInput::make('created_by')
                                    ->label(__('cms.created_by'))
                                    ->numeric()
                                    ->default(auth()->id())
                                    ->disabled(),

                                TextInput::make('updated_by')
                                    ->label(__('cms.updated_by'))
                                    ->numeric()
                                    ->default(auth()->id())
                                    ->disabled(),
                            ]),

                        Section::make('SEO')
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label(__('cms.seo_title'))
                                    ->placeholder(__('cms.seo_title_placeholder'))
                                    ->maxLength(60),

                                Textarea::make('seo_description')
                                    ->label(__('cms.seo_description'))
                                    ->placeholder(__('cms.seo_description_placeholder'))
                                    ->maxLength(160)
                                    ->rows(3),

                                FileUpload::make('og_image')
                                    ->label(__('cms.og_image'))
                                    ->placeholder(__('cms.og_image_placeholder'))
                                    ->image()
                                    ->directory('cms/og-images'),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
