<?php

namespace App\Livewire\Admin\Events;

use App\Models\Event;
use Livewire\Attributes\Computed;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Livewire\Component;
use Illuminate\Support\Str;
use Filament\Support\RawJs;

class RevenueCalculator extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public Event $event;
    public ?array $data = [];

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->form->fill([
            'scenario' => 'optimistic',
            'taxRate' => 10.0,
            'platformFeeRate' => 0.0,
            'enableMerch' => false,
            'merchRevPerUnit' => 0,
            'merchConversionRate' => 0.0,
            'ticketConfig' => $this->getTicketConfig(),
            'costs' => [
                 ['id' => Str::random(), 'name' => 'Venue Rental', 'amount' => 0],
                 ['id' => Str::random(), 'name' => 'Marketing', 'amount' => 0],
                 ['id' => Str::random(), 'name' => 'Artist Fees', 'amount' => 0],
                 ['id' => Str::random(), 'name' => 'Production (Sound/Light)', 'amount' => 0],
            ],
        ]);
    }

    protected function getTicketConfig(): array
    {
        $config = [];
        foreach ($this->event->ticketTypes as $ticketType) {
            $config[] = [
                'id' => $ticketType->id,
                'name' => $ticketType->name,
                'price' => $ticketType->price,
                'quantity' => $ticketType->quantity,
                'is_vip' => str_contains(strtolower($ticketType->name), 'vip'),
            ];
        }
        return $config;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make()
                    ->columns(['lg' => 3])
                    ->schema([
                        // Left Column (Inputs)
                        Group::make()
                            ->columnSpan(['lg' => 2])
                            ->schema([
                                // Scenario & Taxes Row
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Section::make('Scenario Settings')
                                            ->icon('heroicon-m-chart-bar')
                                            ->columnSpan(1)
                                            ->schema([
                                                Radio::make('scenario')
                                                    ->hiddenLabel()
                                                    ->options([
                                                        'optimistic' => 'Optimistic (100% Sales)',
                                                        'pessimistic' => 'Pessimistic (50% Sales)',
                                                    ])
                                                    ->descriptions([
                                                        'optimistic' => 'Assumes full capacity sales.',
                                                        'pessimistic' => 'Conservative estimate.',
                                                    ])
                                                    ->live()
                                                    ->default('optimistic'),
                                            ]),

                                        Section::make('Taxes & Fees')
                                            ->icon('heroicon-m-building-library')
                                            ->columnSpan(1)
                                            ->schema([
                                                Grid::make(1)->schema([
                                                    TextInput::make('taxRate')
                                                        ->label('Entertainment Tax')
                                                        ->numeric()
                                                        ->suffix('%')
                                                        ->default(10)
                                                        ->live(debounce: 500),
                                                    TextInput::make('platformFeeRate')
                                                        ->label('Platform Fee')
                                                        ->numeric()
                                                        ->suffix('%')
                                                        ->default(0)
                                                        ->live(debounce: 500),
                                                ]),
                                            ]),
                                    ]),

                                // Ticket Configuration
                                Section::make('Ticket Sales Configuration')
                                    ->icon('heroicon-m-ticket')
                                    ->schema([
                                        Repeater::make('ticketConfig')
                                            ->hiddenLabel()
                                            ->addable(false)
                                            ->deletable(false)
                                            ->reorderable(false)
                                            ->columns(12)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Type')
                                                    ->columnSpan(4)
                                                    ->disabled()
                                                    ->dehydrated(),
                                                TextInput::make('price')
                                                    ->label('Price')
                                                    ->mask(RawJs::make(<<<'JS'
                                                        $money($input, ',', '.', 0)
                                                    JS))
                                                    ->stripCharacters('.')
                                                    ->rule('numeric')
                                                    ->extraInputAttributes(['inputmode' => 'numeric'])
                                                    ->prefix('IDR')
                                                    ->live(debounce: 500)
                                                    ->columnSpan(3),
                                                TextInput::make('quantity')
                                                    ->label('Qty')
                                                    ->numeric()
                                                    ->live(debounce: 500)
                                                    ->columnSpan(2),
                                                Checkbox::make('is_vip')
                                                    ->label('VIP / Merch')
                                                    ->live()
                                                    ->columnSpan(3)
                                                    ->inline(false),
                                            ]),
                                    ]),

                                // Merchandise
                                Section::make('Merchandise Simulation')
                                    ->icon('heroicon-m-shopping-bag')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            Toggle::make('enableMerch')
                                                ->label('Enable Merchandise Revenue')
                                                ->live()
                                                ->columnSpanFull()
                                                ->inline(false),
                                            
                                            TextInput::make('merchRevPerUnit')
                                                ->label('Profit per Unit')
                                                ->mask(RawJs::make(<<<'JS'
                                                    $money($input, ',', '.', 0)
                                                JS))
                                                ->stripCharacters('.')
                                                ->rule('numeric')
                                                ->extraInputAttributes(['inputmode' => 'numeric'])
                                                ->prefix('IDR')
                                                ->visible(fn (Get $get) => $get('enableMerch'))
                                                ->live(debounce: 500)
                                                ->columnSpan(1),
                                            
                                            TextInput::make('merchConversionRate')
                                                ->label('Conversion Rate (%)')
                                                ->numeric()
                                                ->suffix('%')
                                                ->helperText('% of VIP attendees buying merch')
                                                ->visible(fn (Get $get) => $get('enableMerch'))
                                                ->live(debounce: 500)
                                                ->columnSpan(1),
                                        ]),
                                    ]),
                                
                                // Costs
                                Section::make('Fixed Production Costs')
                                    ->icon('heroicon-m-banknotes')
                                    ->collapsible()
                                    ->schema([
                                        Repeater::make('costs')
                                            ->hiddenLabel()
                                            ->live()
                                            ->addActionLabel('Add Cost Item')
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->placeholder('Item Name')
                                                    ->required()
                                                    ->live(debounce: 500),
                                                TextInput::make('amount')
                                                    ->placeholder('Amount')
                                                    ->mask(RawJs::make(<<<'JS'
                                                        $money($input, ',', '.', 0)
                                                    JS))
                                                    ->stripCharacters('.')
                                                    ->rule('numeric')
                                                    ->extraInputAttributes(['inputmode' => 'numeric'])
                                                    ->prefix('IDR')
                                                    ->live(debounce: 500),
                                            ]),
                                    ]),
                            ]),

                        // Right Column (Sticky Stats)
                        Group::make()
                            ->columnSpan(['lg' => 1])
                            ->schema([
                                ViewField::make('stats')
                                    ->view('livewire.admin.events.revenue-calculator-stats')
                                    ->hiddenLabel()
                                    ->key(fn() => 'stats-' . time()),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    #[Computed]
    public function metrics(): array
    {
        $data = $this->form->getRawState();
        
        $scenario = $data['scenario'] ?? 'optimistic';
        $ticketConfig = $data['ticketConfig'] ?? [];
        $costs = $data['costs'] ?? [];
        $enableMerch = $data['enableMerch'] ?? false;
        $merchRevPerUnit = (float)($data['merchRevPerUnit'] ?? 0);
        $merchConversionRate = (float)($data['merchConversionRate'] ?? 0);
        $taxRate = (float)($data['taxRate'] ?? 10);
        $platformFeeRate = (float)($data['platformFeeRate'] ?? 0);

        $multiplier = match($scenario) {
            'pessimistic' => 0.5,
            default => 1.0,
        };

        // 1. Gross Revenue & Tickets
        $grossTicketRevenue = 0;
        $totalTicketsSold = 0;
        $vipTicketsSold = 0;

        // Helper to parse money string (remove dots)
        $parseMoney = fn($val) => (float) str_replace('.', '', (string) ($val ?? 0));

        foreach ($data['ticketConfig'] ?? [] as $ticket) {
            $quantity = (int) ($ticket['quantity'] ?? 0);
            $price = $parseMoney($ticket['price']);

            // Simple logic: if optimistic, sell all? Or keep logic?
            // "Scenario" logic was: $sold = $quantity * $multiplier
            // Let's keep existing logic but use $multiplier
            $sold = (int) ($quantity * $multiplier); // multiplier is 1.0 or 0.5
            
            $grossTicketRevenue += $sold * $price;
            $totalTicketsSold += $sold;
            
            if (!empty($ticket['is_vip'])) {
                $vipTicketsSold += $sold;
            }
        }

        // 2. Merch Revenue
        $merchRevenue = 0;
        if ($enableMerch) {
            // Merch conversion logic
            $merchBuyers = (int) ($vipTicketsSold * ($merchConversionRate / 100));
            // parse merchRevPerUnit
            $merchRevenue = $merchBuyers * $parseMoney($data['merchRevPerUnit']);
        }

        $totalRevenue = $grossTicketRevenue + $merchRevenue;

        // 3. Expenses
        // Parse fixed costs
        $fixedExpenses = collect($costs)->sum(fn($c) => $parseMoney($c['amount']));
        
        $taxAmount = $grossTicketRevenue * ($taxRate / 100);
        $feeAmount = $grossTicketRevenue * ($platformFeeRate / 100);
        
        $totalExpenses = $fixedExpenses + $taxAmount + $feeAmount;

        // 4. Net Profit
        $netProfit = $totalRevenue - $totalExpenses;

        // 5. Break Even Point
        $breakEvenTickets = 0;
        $avgTicketPrice = $totalTicketsSold > 0 ? $grossTicketRevenue / $totalTicketsSold : 0;
        
        if ($avgTicketPrice > 0) {
            $variableCostPerTicket = $avgTicketPrice * (($taxRate + $platformFeeRate) / 100);
            $contributionMargin = $avgTicketPrice - $variableCostPerTicket;
            
            if ($contributionMargin > 0) {
                 $breakEvenTickets = ceil($fixedExpenses / $contributionMargin);
            }
        }

        return [
            'gross_revenue' => $totalRevenue,
            'ticket_revenue' => $grossTicketRevenue,
            'merch_revenue' => $merchRevenue,
            'total_expenses' => $totalExpenses,
            'fixed_expenses' => $fixedExpenses,
            'tax_amount' => $taxAmount,
            'fee_amount' => $feeAmount,
            'net_profit' => $netProfit,
            'break_even_tickets' => $breakEvenTickets,
            'total_tickets_sold' => $totalTicketsSold,
        ];
    }


    public function render()
    {
        return view('livewire.admin.events.revenue-calculator');
    }
}
