<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use App\Services\FileBucketService;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Dotswan\MapPicker\Fields\Map;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Action as TableAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?int $navigationSort = 1;

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
                Group::make()
                    ->schema([
                        Section::make('Basic Information')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->placeholder('Enter event title')
                                    ->afterStateUpdated(
                                        fn (string $operation, $state, Set $set) =>
                                            $operation === 'create'
                                                ? $set('slug', Str::slug($state))
                                                : null
                                    ),  

                                TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('event-url-slug')
                                    ->unique(Event::class, 'slug', ignoreRecord: true),

                                Select::make('categories')
                                    ->relationship(
                                        'categories', 
                                        'name',
                                        fn (Builder $query) => $query->whereHas('setting', fn ($q) => $q->where('key', 'event_categories'))
                                    )
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select event categories'),

                                RichEditor::make('description')
                                    ->required()
                                    ->minLength(1)
                                    ->placeholder('Provide a detailed description of the event')
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

                        Section::make('Event Details')
                            ->schema([
                                DateTimePicker::make('event_date')
                                    ->required()
                                    ->label('Start Date & Time')
                                    ->placeholder('Select start date and time'),

                                DateTimePicker::make('event_end_date')
                                    ->label('End Date & Time')
                                    ->after('event_date')
                                    ->placeholder('Select end date and time'),

                                TextInput::make('venue_name')
                                    ->placeholder('e.g. Grand Ballroom, Hotel Indonesia')
                                    ->maxLength(255),

                                TextInput::make('location')
                                    ->label('Address / Location Name')
                                    ->required()
                                    ->placeholder('Enter full address or building name')
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                Select::make('city_code')
                                    ->label('City')
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, $livewire) {
                                        if (!$state) {
                                            // Clear coordinates if city is cleared
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
                                            
                                            // Try to get from meta if exists
                                            $meta = is_string($city->meta) ? json_decode($city->meta, true) : $city->meta;
                                            if (isset($meta['lat']) && isset($meta['lng'])) {
                                                $lat = (float)$meta['lat'];
                                                $lng = (float)$meta['lng'];
                                            } elseif (isset($meta['latitude']) && isset($meta['longitude'])) {
                                                $lat = (float)$meta['latitude'];
                                                $lng = (float)$meta['longitude'];
                                            } else {
                                                // Fallback: Public geocoding for better UX
                                                try {
                                                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                                                        'User-Agent' => 'FilamentMapPicker/1.0'
                                                    ])->get("https://nominatim.openstreetmap.org/search", [
                                                        'q' => $city->name . ', Indonesia',
                                                        'format' => 'json',
                                                        'limit' => 1
                                                    ]);
                                                    
                                                    if ($response->successful() && count($response->json()) > 0) {
                                                        $result = $response->json()[0];
                                                        $lat = (float)$result['lat'];
                                                        $lng = (float)$result['lon'];
                                                    }
                                                } catch (\Exception $e) {
                                                    // Log error but don't break the flow
                                                    \Illuminate\Support\Facades\Log::warning('Geocoding failed for city: ' . $city->name, [
                                                        'error' => $e->getMessage()
                                                    ]);
                                                }
                                            }
                                            
                                            if ($lat && $lng) {
                                                // Update latitude and longitude fields
                                                $set('latitude', $lat);
                                                $set('longitude', $lng);
                                                
                                                // Update the map location
                                                $set('location_map', [
                                                    'lat' => $lat,
                                                    'lng' => $lng
                                                ]);

                                                // Force the map to move
                                                $livewire->dispatch('refreshMap');
                                            }
                                        }
                                    })
                                    ->getSearchResultsUsing(fn (string $search): array => \Laravolt\Indonesia\Models\City::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'code')->toArray())
                                    ->getOptionLabelUsing(fn ($value): ?string => \Laravolt\Indonesia\Models\City::where('code', $value)->first()?->name)
                                    ->required()
                                    ->columnSpanFull(),

                                Map::make('location_map')
                                    ->label('Pick Location on Map')
                                    ->columnSpanFull()
                                    ->draggable(true)
                                    ->clickable(true)
                                    ->zoom(15)
                                    ->minZoom(0)
                                    ->maxZoom(28)
                                    ->detectRetina(true)
                                    ->defaultLocation(latitude: -6.2088, longitude: 106.8456)
                                    ->afterStateHydrated(function ($set, $record) {
                                        if ($record && $record->latitude && $record->longitude) {
                                            $set('location_map', ['lat' => (float)$record->latitude, 'lng' => (float)$record->longitude]);
                                        }
                                    })
                                    ->afterStateUpdated(function ($set, $state) {
                                        if ($state) {
                                            $set('latitude', $state['lat']);
                                            $set('longitude', $state['lng']);
                                        }
                                    })
                                    // Search Controls
                                    ->showMyLocationButton()
                                    ->showFullscreenControl(true)
                                    ->showZoomControl(true)
                                    ->rangeSelectField('distance')
                                    ->live(),

                                Group::make()
                                    ->schema([
                                        TextInput::make('latitude')
                                            ->numeric()
                                            ->required()
                                            ->placeholder('-6.20880000')
                                            ->step('any')
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, $get, $livewire) {
                                                if ($state && $get('longitude')) {
                                                    $set('location_map', [
                                                        'lat' => (float)$state,
                                                        'lng' => (float)$get('longitude')
                                                    ]);
                                                    $livewire->dispatch('refreshMap');
                                                }
                                            }),
                                        TextInput::make('longitude')
                                            ->numeric()
                                            ->required()
                                            ->placeholder('106.84560000')
                                            ->step('any')
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, $get, $livewire) {
                                                if ($state && $get('latitude')) {
                                                    $set('location_map', [
                                                        'lat' => (float)$get('latitude'),
                                                        'lng' => (float)$state
                                                    ]);
                                                    $livewire->dispatch('refreshMap');
                                                }
                                            }),
                                    ])
                                    ->columns(2),
                            ]),

                        Section::make('Media')
                            ->schema([
                                FileUpload::make('banner_image')
                                    ->label('Event Banner')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('event-images')
                                    ->visibility('public')
                                    ->maxSize(5120) // 5MB

                                    ->multiple()
                                    ->reorderable()
                                    ->openable()
                                    ->downloadable()

                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Recommended size: 1200x600px (2:1 ratio). Max 5MB.')
                                    ->afterStateHydrated(fn (FileUpload $component, ?\Illuminate\Database\Eloquent\Model $record) => $component->state($record?->banners->pluck('file_path')->toArray() ?? []))
                                    ->dehydrated(true),
                                    // ->required(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status & Visibility')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'sold_out' => 'Sold Out',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->placeholder('Select event status'),

                                DateTimePicker::make('published_at')
                                    ->visible(fn ($get) => $get('status') === 'published')
                                    ->placeholder('Schedule publication date'),
                            ]),

                        Section::make('Sales Configuration')
                            ->schema([
                                DatePicker::make('sales_start_at')
                                    ->placeholder('When tickets go on sale'),
                                DatePicker::make('sales_end_at')
                                    ->placeholder('When ticket sales end'),
                                Toggle::make('seating_enabled')
                                    ->label('Enable Seating Layout')
                                    ->default(false),
                                Select::make('tags')
                                    ->relationship(
                                        'tags', 
                                        'name',
                                        fn (Builder $query) => $query->whereHas('setting', fn ($q) => $q->where('key', 'event_tags'))
                                    )
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select event tags'),
                                
                                Section::make('Ticket Types')
                                    ->schema([
                                        Repeater::make('ticketTypes')
                                            ->relationship('ticketTypes')
                                            ->schema([
                                                Select::make('name')
                                                    ->options(\App\Models\SettingComponent::whereHas('setting', fn ($q) => $q->where('key', 'ticket_types'))->pluck('name', 'name'))
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->placeholder('Select ticket type')
                                                    ->columnSpanFull(),
                                                TextInput::make('price')
                                                    ->numeric()
                                                    ->prefix('IDR')
                                                    ->required()
                                                    ->placeholder('0.00')
                                                    ->columnSpanFull(),
                                                Grid::make(3)
                                                    ->schema([
                                                        TextInput::make('quantity')
                                                            ->numeric()
                                                            ->required()
                                                            ->placeholder('100'),
                                                        TextInput::make('min_purchase')
                                                            ->numeric()
                                                            ->label('Min')
                                                            ->default(1)
                                                            ->required(),
                                                        TextInput::make('max_purchase')
                                                            ->numeric()
                                                            ->label('Max')
                                                            ->default(10)
                                                            ->required(),
                                                    ]),
                                                Grid::make(2)
                                                    ->schema([
                                                        DatePicker::make('sales_start_at')
                                                            ->label('Sales Start')
                                                            ->placeholder('Start date'),
                                                        DatePicker::make('sales_end_at')
                                                            ->label('Sales End')
                                                            ->placeholder('End date'),
                                                    ]),
                                                Toggle::make('is_active')
                                                    ->label('Active for Sale')
                                                    ->default(true),
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                            ->defaultItems(1)
                                            ->columnSpanFull(),
                                    ])
                                    ->compact(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner.url')
                    ->label('Banner'),
                
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city.name')
                    ->label('City')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->sortable(),

                TextColumn::make('event_date')
                    ->dateTime()
                    ->sortable(),

                BadgeColumn::make('status')
                ->colors([
                    'secondary' => 'draft',
                    'warning' => 'sold_out',
                    'success' => 'published',
                    'danger' => 'cancelled',
                ]),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'sold_out' => 'Sold Out',
                    'cancelled' => 'Cancelled',
                ]),
                
            TrashedFilter::make(),
        ])
        ->actions([
            TableAction::make('scan')
                ->label(__('scanner.start_scanning'))
                ->icon('heroicon-o-qr-code')
                ->color('success')
                ->url(fn () => \App\Filament\Pages\ScanTickets::getUrl()),
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
            // RelationManagers\TicketTypesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
