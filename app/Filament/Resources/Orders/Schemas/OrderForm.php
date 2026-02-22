<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\TicketType;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                                    ->addable(false)
                                    ->deletable(false)
                                    ->disabled()
                                    ->live()
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        self::updateTotals($get, $set);
                                    }),
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
