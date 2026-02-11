<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\TicketType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('order_number')
                                    ->label(__('order.order_number'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->default('ORD-' . now()->format('Ymd') . '-XXXXXX'),
                                Select::make('event_id')
                                    ->label(__('order.event'))
                                    ->relationship('event', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->disabled()
                                    ->dehydrated()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('items', []);
                                    })
                                    ->required(),
                            ])
                            ->columns(2),
                        Section::make()
                            ->schema([
                                Repeater::make('items')
                                    ->label(__('order.items'))
                                    ->relationship('orderItems')
                                    ->schema([
                                        Select::make('ticket_type_id')
                                            ->label(__('order.ticket_type'))
                                            ->options(fn ($get) => TicketType::where('event_id', $get('../../event_id'))
                                                ->where('is_active', true)
                                                ->with(['event'])
                                                ->get()
                                                ->mapWithKeys(fn ($ticketType) => [$ticketType->id => $ticketType->name]))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $ticketType = TicketType::find($state);
                                                if ($ticketType) {
                                                    $set('unit_price', $ticketType->price);
                                                }
                                            })
                                            ->columnSpan(3),
                                        TextInput::make('quantity')
                                            ->label(__('order.quantity'))
                                            ->numeric()
                                            ->minValue(1)
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                                $unitPrice = $get('unit_price') ?? 0;
                                                $set('total_price', $unitPrice * $state);
                                            })
                                            ->columnSpan(2),
                                        TextInput::make('unit_price')
                                            ->label(__('order.unit_price'))
                                            ->numeric()
                                            ->prefix('IDR ')
                                            ->readOnly()
                                            ->dehydrated()
                                            ->columnSpan(3),
                                        TextInput::make('total_price')
                                            ->label(__('order.total_price'))
                                            ->numeric()
                                            ->prefix('IDR ')
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan(4),
                                    ])
                                    ->columns(12)
                                    ->required()
                                    ->createItemButtonLabel(__('order.add_item'))
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::updateTotals($get, $set);
                                    }),
                            ]),
                        Section::make(__('order.payment_proofs'))
                            ->schema([
                                Repeater::make('files')
                                    ->relationship('files')
                                    ->label(__('order.payment_proofs'))
                                    ->schema([
                                        FileUpload::make('file_path')
                                            ->label(__('order.proof_file'))
                                            ->disk('public')
                                            ->directory('payment-proofs')
                                            ->image()
                                            ->required()
                                            ->storeFileNamesIn('original_filename')
                                            ->columnSpanFull(),
                                        Hidden::make('bucket_type')
                                            ->default('payment_proof'),
                                    ])
                                    ->grid(3)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['original_filename'] ?? null),
                            ])
                            ->collapsible(),
                        Section::make()
                            ->schema([
                                Select::make('status')
                                    ->label(__('order.status_label'))
                                    ->options([
                                        'pending_payment' => __('order.status.pending_payment'),
                                        'pending_verification' => __('order.status.pending_verification'),
                                        'completed' => __('order.status.completed'),
                                        'cancelled' => __('order.status.cancelled'),
                                        'expired' => __('order.status.expired'),
                                    ])
                                    ->default('pending_payment')
                                    ->live()
                                    ->required()
                                    ->rules([
                                        function (callable $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                if ($value === 'completed' && blank($get('files'))) {
                                                    $fail(__('order.validation.payment_proof_required'));
                                                }
                                            };
                                        },
                                    ]),
                                Textarea::make('cancellation_reason')
                                    ->label(__('order.cancellation_notes'))
                                    ->placeholder(__('order.cancellation_notes_placeholder'))
                                    ->visible(fn (callable $get) => $get('status') === 'cancelled')
                                    ->required()
                                    ->validationAttribute(__('order.cancellation_notes'))
                                    ->rows(3),
                                DateTimePicker::make('expires_at')
                                    ->label(__('order.expires_at'))
                                    ->default(now()->addHours(24)),
                                DateTimePicker::make('completed_at')
                                    ->label(__('order.completed_at')),
                            ]),
                    ])
                    ->columnSpan(['xl' => 2]),

                Group::make()
                    ->schema([
                        Section::make(__('order.pricing_summary'))
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label(__('order.subtotal'))
                                    ->numeric()
                                    ->prefix('IDR ')
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0.0),
                                TextInput::make('discount_amount')
                                    ->label(__('order.discount_amount'))
                                    ->numeric()
                                    ->prefix('IDR ')
                                    ->default(0.0)
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::updateTotals($get, $set);
                                    }),
                                TextInput::make('tax_amount')
                                    ->label(__('order.tax_amount'))
                                    ->numeric()
                                    ->prefix('IDR ')
                                    ->default(0.0)
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::updateTotals($get, $set);
                                    }),
                                TextInput::make('total_amount')
                                    ->label(__('order.total_amount'))
                                    ->numeric()
                                    ->prefix('IDR ')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->default(0.0)
                                    ->extraInputAttributes(['class' => 'text-primary-600 font-bold text-lg']),
                            ])
                            ->columns(1),
                        Section::make(__('order.user'))
                            ->schema([
                                Select::make('user_id')
                                    ->label(__('order.user'))
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->hiddenLabel(),
                            ]),
                        Section::make(__('order.notes'))
                            ->schema([
                                Textarea::make('notes')
                                    ->label(__('order.notes'))
                                    ->placeholder(__('order.notes_placeholder'))
                                    ->rows(4)
                                    ->hiddenLabel(),
                            ]),             
                    ])
                    ->columnSpan(['xl' => 1]),
            ])
            ->columns(3);
    }

    public static function updateTotals(callable $get, callable $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) ($item['total_price'] ?? 0);
        }
        $set('subtotal', $subtotal);
        $discount = (float) ($get('discount_amount') ?? 0);
        $tax = (float) ($get('tax_amount') ?? 0);
        $set('total_amount', $subtotal - $discount + $tax);
    }
}
