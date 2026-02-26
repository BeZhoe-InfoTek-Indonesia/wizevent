<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Dotswan\MapPicker\Fields\Map;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?int $navigationSort = 1;

    public static function getMaxContentWidth(): string
    {
        return 'full';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.event_management');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Wizard\Step::make('basic_information')
                        ->label(__('event.steps.basic_information'))
                        ->description(__('event.steps.basic_information_description'))
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label(__('event.labels.title'))
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->placeholder(__('event.placeholders.title'))
                                        ->afterStateUpdated(
                                            fn (string $operation, $state, $set) => $operation === 'create'
                                                    ? $set('slug', Str::slug($state))
                                                    : null
                                        ),

                                    TextInput::make('slug')
                                        ->label(__('event.labels.slug'))
                                        ->disabled()
                                        ->dehydrated()
                                        ->required()
                                        ->maxLength(255)
                                        ->placeholder(__('event.placeholders.slug'))
                                        ->unique(Event::class, 'slug', ignoreRecord: true),
                                ]),

                            TextInput::make('short_description')
                                ->label(__('event.labels.short_description'))
                                ->maxLength(500)
                                ->placeholder(__('event.placeholders.short_description'))
                                ->columnSpanFull()
                                ->suffixActions([
                                    Action::make('generate_short_description')
                                        ->label(__('event.buttons.generate_short_description'))
                                        ->icon('heroicon-o-sparkles')
                                        ->color('primary')
                                        ->visible(fn ($get) => !empty($get('description')))
                                        ->action(function ($get, $set) {
                                            $description = $get('description');
                                            $title = $get('title');

                                            if (empty($description)) {
                                                return;
                                            }

                                            try {
                                                $aiService = app(\App\Services\AiService::class);
                                                $shortDescription = $aiService->generateShortDescription([
                                                    'description' => $description,
                                                    'title' => $title,
                                                ]);

                                                if ($shortDescription) {
                                                    $set('short_description', $shortDescription);
                                                    \Filament\Notifications\Notification::make()
                                                        ->success()
                                                        ->title(__('event.notifications.short_description_generated'))
                                                        ->send();
                                                } else {
                                                    \Filament\Notifications\Notification::make()
                                                        ->warning()
                                                        ->title(__('event.notifications.short_description_generation_failed'))
                                                        ->send();
                                                }
                                            } catch (\Exception $e) {
                                                \Illuminate\Support\Facades\Log::error('Failed to generate short description: ' . $e->getMessage());
                                                \Filament\Notifications\Notification::make()
                                                    ->danger()
                                                    ->title(__('event.notifications.short_description_generation_error'))
                                                    ->body($e->getMessage())
                                                    ->send();
                                            }
                                        }),
                                ]),


                            Select::make('categories')
                                ->label(__('event.labels.categories'))
                                ->relationship(
                                    'categories',
                                    'name',
                                    fn (Builder $query) => $query->whereHas('setting', fn ($q) => $q->where('key', 'event_categories'))
                                )
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->placeholder(__('event.placeholders.categories'))
                                ->columnSpanFull(),

                            RichEditor::make('description')
                                ->label(__('event.labels.description'))
                                ->statePath('description')
                                ->required()
                                ->minLength(1)
                                ->placeholder(__('event.placeholders.description'))
                                ->columnSpanFull()
                                ->floatingToolbars([
                                    'paragraph' => [
                                        'bold', 'italic', 'underline', 'strike', 'subscript', 'superscript',
                                    ],
                                    'heading' => [
                                        'h1', 'h2', 'h3',
                                    ],
                                ])
                                ->toolbarButtons(
                                    [
                                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                        ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                                        ['blockquote', 'bulletList', 'orderedList'],
                                        ['undo', 'redo'],
                                    ]
                                ),
                        ]),

                    Wizard\Step::make('location_time')
                        ->label(__('event.steps.location_time'))
                        ->description(__('event.steps.location_time_description'))
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    DateTimePicker::make('event_date')
                                        ->required()
                                        ->label(__('event.labels.start_date_time'))
                                        ->placeholder(__('event.placeholders.start_date_time')),

                                    DateTimePicker::make('event_end_date')
                                        ->label(__('event.labels.end_date_time'))
                                        ->after('event_date')
                                        ->placeholder(__('event.placeholders.end_date_time')),
                                ]),

                            TextInput::make('venue_name')
                                ->label(__('event.labels.venue_name'))
                                ->placeholder(__('event.placeholders.venue_name'))
                                ->maxLength(255),

                            TextInput::make('location')
                                ->label(__('event.labels.location'))
                                ->required()
                                ->placeholder(__('event.placeholders.location'))
                                ->maxLength(500)
                                ->columnSpanFull(),

                            Select::make('city_code')
                                ->label(__('event.labels.city'))
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, $set, $livewire) {
                                    if (! $state) {
                                        $set('latitude', null);
                                        $set('longitude', null);
                                        $set('location_map', null);
                                        $livewire->dispatch('refreshMap');

                                        return;
                                    }

                                    $city = \Laravolt\Indonesia\Models\City::where('code', $state)->first();
                                    if ($city) {
                                        $lat = null;
                                        $lng = null;

                                        $meta = is_string($city->meta) ? json_decode($city->meta, true) : $city->meta;
                                        if (isset($meta['lat']) && isset($meta['lng'])) {
                                            $lat = (float) $meta['lat'];
                                            $lng = (float) $meta['lng'];
                                        } elseif (isset($meta['latitude']) && isset($meta['longitude'])) {
                                            $lat = (float) $meta['latitude'];
                                            $lng = (float) $meta['longitude'];
                                        } else {
                                            try {
                                                $response = \Illuminate\Support\Facades\Http::withHeaders([
                                                    'User-Agent' => 'FilamentMapPicker/1.0',
                                                ])->get('https://nominatim.openstreetmap.org/search', [
                                                    'q' => $city->name.', Indonesia',
                                                    'format' => 'json',
                                                    'limit' => 1,
                                                ]);

                                                if ($response->successful() && count($response->json()) > 0) {
                                                    $result = $response->json()[0];
                                                    $lat = (float) $result['lat'];
                                                    $lng = (float) $result['lon'];
                                                }
                                            } catch (\Exception $e) {
                                                \Illuminate\Support\Facades\Log::warning('Geocoding failed for city: '.$city->name, [
                                                    'error' => $e->getMessage(),
                                                ]);
                                            }
                                        }

                                        if ($lat && $lng) {
                                            $set('latitude', $lat);
                                            $set('longitude', $lng);
                                            $set('location_map', [
                                                'lat' => $lat,
                                                'lng' => $lng,
                                            ]);
                                            $livewire->dispatch('refreshMap');
                                        }
                                    }
                                })
                                ->getSearchResultsUsing(fn (string $search): array => \Laravolt\Indonesia\Models\City::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'code')->toArray())
                                ->getOptionLabelUsing(fn ($value): ?string => \Laravolt\Indonesia\Models\City::where('code', $value)->first()?->name)
                                ->required()
                                ->columnSpanFull(),

                            Map::make('location_map')
                                ->label(__('event.labels.location_map'))
                                ->columnSpanFull()
                                ->draggable(true)
                                ->clickable(true)
                                ->zoom(15)
                                ->minZoom(0)
                                ->maxZoom(28)
                                ->detectRetina(true)
                                ->defaultLocation(latitude: -6.2088, longitude: 106.8456),

                            Group::make()
                                ->schema([
                                    TextInput::make('latitude')
                                        ->label(__('event.labels.latitude'))
                                        ->numeric()
                                        ->required()
                                        ->placeholder(__('event.placeholders.latitude'))
                                        ->step('any')
                                        ->live(),
                                    TextInput::make('longitude')
                                        ->label(__('event.labels.longitude'))
                                        ->numeric()
                                        ->required()
                                        ->placeholder(__('event.placeholders.longitude'))
                                        ->step('any')
                                        ->live(),
                                ])
                                ->columns(2),
                        ]),

                    Wizard\Step::make('media_seo')
                        ->label(__('event.steps.media_seo'))
                        ->description(__('event.steps.media_seo_description'))
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    FileUpload::make('banner_image')
                                        ->label(__('event.labels.banner'))
                                        ->image()
                                        ->imageEditor()
                                        ->disk('public')
                                        ->directory('event-images')
                                        ->visibility('public')
                                        ->maxSize(5120)
                                        ->multiple()
                                        ->reorderable()
                                        ->openable()
                                        ->downloadable()
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText(__('event.helper_texts.banner'))
                                        ->afterStateHydrated(fn (FileUpload $component, ?\Illuminate\Database\Eloquent\Model $record) => $component->state($record ? $record->banners->pluck('file_path')->toArray() : []))
                                        ->dehydrated(true),

                                    Group::make()
                                        ->schema([
                                            TextInput::make('seoMetadata.title')
                                                ->label(__('event.labels.seo_title'))
                                                ->maxLength(60)
                                                ->placeholder(__('event.placeholders.seo_title'))
                                                ->helperText(__('event.helper_texts.seo_title')),

                                            TextInput::make('seoMetadata.description')
                                                ->label(__('event.labels.seo_description'))
                                                ->maxLength(160)
                                                ->placeholder(__('event.placeholders.seo_description'))
                                                ->helperText(__('event.helper_texts.seo_description')),

                                            FileUpload::make('seoMetadata.og_image')
                                                ->label(__('event.labels.og_image'))
                                                ->image()
                                                ->disk('public')
                                                ->directory('seo-images')
                                                ->visibility('public')
                                                ->maxSize(5120)
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->helperText(__('event.helper_texts.og_image')),

                                            TextInput::make('seoMetadata.keywords')
                                                ->label(__('event.labels.seo_keywords'))
                                                ->placeholder(__('event.placeholders.seo_keywords'))
                                                ->helperText(__('event.helper_texts.seo_keywords')),

                                            TextInput::make('seoMetadata.canonical_url')
                                                ->label(__('event.labels.canonical_url'))
                                                ->url()
                                                ->placeholder(__('event.placeholders.canonical_url'))
                                                ->helperText(__('event.helper_texts.canonical_url')),
                                        ])
                                        ->columnSpan(2),
                                ]),
                        ]),

                    Wizard\Step::make('sales_configuration')
                        ->label(__('event.steps.sales_configuration'))
                        ->description(__('event.steps.sales_configuration_description'))
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    Select::make('status')
                                        ->label(__('event.labels.status'))
                                        ->options([
                                            'draft' => __('event.status.draft'),
                                            'published' => __('event.status.published'),
                                            'sold_out' => __('event.status.sold_out'),
                                            'cancelled' => __('event.status.cancelled'),
                                        ])
                                        ->default('draft')
                                        ->required()
                                        ->placeholder(__('event.placeholders.status')),

                                    DateTimePicker::make('published_at')
                                        ->label(__('event.labels.published_at'))
                                        ->visible(fn ($get) => $get('status') === 'published')
                                        ->placeholder(__('event.placeholders.published_at')),

                                    DatePicker::make('sales_start_at')
                                        ->label(__('event.labels.sales_start_at'))
                                        ->placeholder(__('event.placeholders.sales_start_at')),
                                    DatePicker::make('sales_end_at')
                                        ->label(__('event.labels.sales_end_at'))
                                        ->placeholder(__('event.placeholders.sales_end_at')),
                                    Toggle::make('seating_enabled')
                                        ->label(__('event.labels.seating_enabled'))
                                        ->default(false),
                                    Select::make('tags')
                                        ->label(__('event.labels.tags'))
                                        ->relationship(
                                            'tags',
                                            'name',
                                            fn (Builder $query) => $query->whereHas('setting', fn ($q) => $q->where('key', 'event_tags'))
                                        )
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->placeholder(__('event.placeholders.tags'))
                                        ->columnSpan(3),

                                    Select::make('rules')
                                        ->label(__('event.labels.rules'))
                                        ->relationship(
                                            'rules',
                                            'name',
                                            fn (Builder $query) => $query->whereHas('setting', fn ($q) => $q->where('key', 'terms_&_condition'))
                                        )
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->placeholder(__('event.placeholders.rules'))
                                        ->columnSpan(3),
                                ]),

                            Section::make(__('event.labels.ticket_types'))
                                ->schema([
                                    Repeater::make('ticketTypes')
                                        ->relationship('ticketTypes')
                                        ->schema([
                                            Select::make('name')
                                                ->label(__('event.labels.ticket_name'))
                                                ->options(\App\Models\SettingComponent::whereHas('setting', fn ($q) => $q->where('key', 'ticket_types'))->pluck('name', 'name'))
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->placeholder(__('event.placeholders.ticket_type'))
                                                ->columnSpanFull(),
                                            TextInput::make('price')
                                                ->label(__('event.labels.price'))
                                                ->numeric()
                                                ->prefix('IDR')
                                                ->required()
                                                ->placeholder(__('event.placeholders.price'))
                                                ->columnSpanFull(),
                                            Grid::make(3)
                                                ->schema([
                                                    TextInput::make('quantity')
                                                        ->label(__('event.labels.quantity'))
                                                        ->numeric()
                                                        ->required()
                                                        ->placeholder(__('event.placeholders.quantity')),
                                                    TextInput::make('min_purchase')
                                                        ->label(__('event.labels.min_purchase'))
                                                        ->numeric()
                                                        ->default(1)
                                                        ->required(),
                                                    TextInput::make('max_purchase')
                                                        ->label(__('event.labels.max_purchase'))
                                                        ->numeric()
                                                        ->default(10)
                                                        ->required(),
                                                ]),
                                            Grid::make(2)
                                                ->schema([
                                                    DatePicker::make('sales_start_at')
                                                        ->label(__('event.labels.sales_start'))
                                                        ->placeholder(__('event.placeholders.ticket_sales_start')),
                                                    DatePicker::make('sales_end_at')
                                                        ->label(__('event.labels.sales_end'))
                                                        ->placeholder(__('event.placeholders.ticket_sales_end')),
                                                ]),
                                            Toggle::make('is_active')
                                                ->label(__('event.labels.is_active'))
                                                ->default(true),
                                        ])
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                        ->defaultItems(1)
                                        ->columnSpanFull(),
                                ])
                                ->compact(),
                        ]),

                    Wizard\Step::make('organizer_payment')
                        ->label(__('event.steps.organizer_payment'))
                        ->description(__('event.steps.organizer_payment_description'))
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            Select::make('organizers')
                                ->label(__('event.labels.organizers'))
                                ->relationship('organizers', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->placeholder(__('event.placeholders.organizers'))
                                ->columnSpanFull(),

                            Select::make('performers')
                                ->label(__('event.labels.performers'))
                                ->relationship('performers', 'name', fn (Builder $query) => $query->with('profession'))
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->profession?->name})")
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->placeholder(__('event.placeholders.performers'))
                                ->columnSpanFull(),

                            Select::make('paymentBanks')
                                ->label(__('event.labels.payment_banks'))
                                ->relationship('paymentBanks', 'bank_name')
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->bank_name} - {$record->account_number}")
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->placeholder(__('event.placeholders.payment_banks'))
                                ->columnSpanFull(),
                        ]),
                ])
                    ->columnSpanFull()
                    ->maxWidth('full')
                    ->skippable()
                    ->startOnStep(1)
                    ->submitAction(new HtmlString('<button type="submit" class="fi-btn fi-btn-size-md fi-btn-color-primary fi-color-primary fi-color-custom fi-btn-has-label"><span class="fi-btn-label">'.__('event.buttons.save_changes').'</span></button>')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner.url')
                    ->label(__('event.labels.banner')),

                TextColumn::make('title')
                    ->label(__('event.labels.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city.name')
                    ->label(__('event.labels.city'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categories.name')
                    ->label(__('event.labels.categories'))
                    ->badge(),

                TextColumn::make('event_date')
                    ->label(__('event.labels.start_date_time'))
                    ->dateTime()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label(__('event.labels.status'))
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'sold_out',
                        'success' => 'published',
                        'danger' => 'cancelled',
                    ]),

                TextColumn::make('created_at')
                    ->label(__('event.labels.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('event.labels.status'))
                    ->options([
                        'draft' => __('event.status.draft'),
                        'published' => __('event.status.published'),
                        'sold_out' => __('event.status.sold_out'),
                        'cancelled' => __('event.status.cancelled'),
                    ]),

                TrashedFilter::make(),
            ])
            ->actions([
                Action::make('scan')
                    ->label(__('scanner.start_scanning'))
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->visible(fn (Event $record) => 
                        $record->status === 'published' && 
                        ($record->event_end_date ? $record->event_end_date->isFuture() : $record->event_date->isFuture())
                    )
                    ->url(fn () => \App\Filament\Pages\ScanTickets::getUrl()),
                Action::make('event-summary')
                    ->label(__('event.actions.event_summary'))
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->visible(fn (Event $record) => $record->status === 'published')
                    ->modalContent(fn (Event $record): \Illuminate\View\View => view('filament.event-summary-modal', ['event' => $record]))
                    ->modalHeading(fn (Event $record) => __('event.actions.event_summary_heading', ['event' => $record->title]))
                    ->modalSubmitAction(false),
                Action::make('revenue-calculator')
                    ->label(__('event.actions.revenue_calculator'))
                    ->icon('heroicon-o-calculator')
                    ->color('info')
                    ->modalContent(fn (Event $record): \Illuminate\View\View => view('components.revenue-calculator-modal', ['event' => $record]))
                    ->modalHeading(__('event.actions.revenue_calculator_modal_heading'))
                    ->modalWidth('7xl')
                    ->modalFooterActions([]),
                EditAction::make(),
                DeleteAction::make()
                    ->using(fn (Model $record) => DB::transaction(fn () => $record->delete())),
                ForceDeleteAction::make()
                    ->using(fn (Model $record) => DB::transaction(fn () => $record->forceDelete())),
                RestoreAction::make()
                    ->using(fn (Model $record) => DB::transaction(fn () => $record->restore())),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->using(fn (\Illuminate\Support\Collection $records) => DB::transaction(fn () => $records->each->delete())),
                    ForceDeleteBulkAction::make()
                        ->using(fn (\Illuminate\Support\Collection $records) => DB::transaction(fn () => $records->each->forceDelete())),
                    RestoreBulkAction::make()
                        ->using(fn (\Illuminate\Support\Collection $records) => DB::transaction(fn () => $records->each->restore())),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'results' => Pages\ViewEventResults::route('/{record}/results'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return ! ($record->event_end_date ? $record->event_end_date->isPast() : $record->event_date->isPast());
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
