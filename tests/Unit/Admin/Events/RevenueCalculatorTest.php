<?php

namespace Tests\Unit\Admin\Events;

use App\Livewire\Admin\Events\RevenueCalculator;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RevenueCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_metrics_correctly()
    {
        dump(DB::getDriverName());
        $event = Event::factory()->create();
        
        TicketType::factory()->create([
            'event_id' => $event->id,
            'name' => 'General',
            'price' => 50,
            'quantity' => 100,
        ]);

        TicketType::factory()->create([
            'event_id' => $event->id,
            'name' => 'VIP',
            'price' => 100,
            'quantity' => 20,
        ]);

        Livewire::test(RevenueCalculator::class, ['event' => $event])
            // Scenario: Optimistic (100% sales)
            // Revenue: (100 * 50) + (20 * 100) = 5000 + 2000 = 7000
            // Tax: 10% of 7000 = 700
            // Fee: 0%
            // Fixed Costs: 0
            // Total Expenses: 700
            // Net Profit: 6300
            ->assertSet('scenario', 'optimistic')
            ->assertSet('taxRate', 10.0)
            ->assertSet('platformFeeRate', 0.0)
            ->assertComputed('metrics', function ($metrics) {
                return $metrics['gross_revenue'] === 7000.0
                    && $metrics['tax_amount'] === 700.0
                    && $metrics['net_profit'] === 6300.0;
            });
    }

    public function test_pessimistic_scenario()
    {
        $event = Event::factory()->create();
        
        TicketType::factory()->create([
            'event_id' => $event->id,
            'name' => 'General',
            'price' => 50,
            'quantity' => 100,
        ]);

        Livewire::test(RevenueCalculator::class, ['event' => $event])
            ->set('scenario', 'pessimistic') // 50% sales
            // 50 tickets * 50 = 2500
            ->assertComputed('metrics', function ($metrics) {
                return $metrics['gross_revenue'] === 2500.0;
            });
    }

    public function test_merch_revenue()
    {
        $event = Event::factory()->create();
        
        TicketType::factory()->create([
            'event_id' => $event->id,
            'name' => 'VIP Pass',
            'price' => 100,
            'quantity' => 10,
        ]);

        Livewire::test(RevenueCalculator::class, ['event' => $event])
            ->set('enableMerch', true)
            ->set('merchRevPerUnit', 20)
            ->set('merchConversionRate', 50.0) // 50% of 10 VIPs = 5 buyers
            // Merch Revenue = 5 * 20 = 100
            // Ticket Revenue = 10 * 100 = 1000
            // Total = 1100
            ->assertComputed('metrics', function ($metrics) {
                return $metrics['merch_revenue'] === 100
                    && $metrics['gross_revenue'] === 1100.0;
            });
    }
}
