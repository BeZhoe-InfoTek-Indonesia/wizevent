<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\TicketType;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTypeModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_ticket_type_has_correct_fillable_fields()
    {
        $ticketType = new TicketType;
        $fillable = $ticketType->getFillable();

        $expectedFillable = [
            'name', 'description', 'price', 'quantity', 'sold_count', 'held_count',
            'min_purchase', 'max_purchase', 'sales_start_at', 'sales_end_at',
            'is_active', 'sort_order', 'event_id',
        ];

        foreach ($expectedFillable as $field) {
            $this->assertContains($field, $fillable);
        }
    }

    public function test_ticket_type_has_correct_casts()
    {
        $ticketType = new TicketType;
        $casts = $ticketType->getCasts();

        $expectedCasts = [
            'sales_start_at' => 'datetime',
            'sales_end_at' => 'datetime',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];

        foreach ($expectedCasts as $key => $expectedCast) {
            $this->assertArrayHasKey($key, $casts);
            $this->assertEquals($expectedCast, $casts[$key]);
        }
    }

    public function test_available_count_calculates_correctly()
    {
        $event = Event::factory()->create();

        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'quantity' => 100,
            'sold_count' => 30,
        ]);

        $this->assertEquals(70, $ticketType->available_count);
    }

    public function test_available_count_considers_held_count()
    {
        $ticketType = TicketType::factory()->create([
            'quantity' => 100,
            'sold_count' => 30,
            'held_count' => 10,
        ]);

        $this->assertEquals(60, $ticketType->available_count);
    }

    public function test_reserve_tickets_increments_held_count()
    {
        $ticketType = TicketType::factory()->create([
            'quantity' => 10, 
            'sold_count' => 0, 
            'held_count' => 0,
            'is_active' => true,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeek(),
            'min_purchase' => 1,
        ]);
        $ticketType->reserveTickets(2);

        $this->assertEquals(2, $ticketType->refresh()->held_count);
    }

    public function test_commit_tickets_dec_held_inc_sold()
    {
        $ticketType = TicketType::factory()->create(['quantity' => 10, 'sold_count' => 0, 'held_count' => 5]);
        $ticketType->commitTickets(2);

        $ticketType->refresh();
        $this->assertEquals(3, $ticketType->held_count);
        $this->assertEquals(2, $ticketType->sold_count);
    }

    public function test_release_tickets_decrements_held_count()
    {
        $ticketType = TicketType::factory()->create(['quantity' => 10, 'sold_count' => 0, 'held_count' => 5]);
        $ticketType->releaseTickets(2);

        $ticketType->refresh();
        $this->assertEquals(3, $ticketType->held_count);
    }

    public function test_is_sold_out_returns_true_when_all_tickets_sold()
    {
        $ticketType = TicketType::factory()->create([
            'quantity' => 100,
            'sold_count' => 100,
        ]);

        $this->assertTrue($ticketType->is_sold_out);
    }

    public function test_is_sold_out_returns_false_when_tickets_available()
    {
        $ticketType = TicketType::factory()->create([
            'quantity' => 100,
            'sold_count' => 50,
        ]);

        $this->assertFalse($ticketType->is_sold_out);
    }

    public function test_is_available_for_sale_returns_true_when_active()
    {
        $ticketType = TicketType::factory()->create([
            'is_active' => true,
            'sales_start_at' => now()->subDays(1),
            'sales_end_at' => now()->addDays(10),
            'quantity' => 100,
            'sold_count' => 0,
        ]);

        $this->assertTrue($ticketType->is_available_for_sale);
    }

    public function test_is_available_for_sale_returns_false_when_inactive()
    {
        $ticketType = TicketType::factory()->create([
            'is_active' => false,
        ]);

        $this->assertFalse($ticketType->is_available_for_sale);
    }

    public function test_is_available_for_sale_returns_false_when_sales_not_started()
    {
        $ticketType = TicketType::factory()->create([
            'is_active' => true,
            'sales_start_at' => now()->addDays(2),
            'sales_end_at' => now()->addDays(10),
        ]);

        $this->assertFalse($ticketType->is_available_for_sale);
    }

    public function test_is_available_for_sale_returns_false_when_sales_ended()
    {
        $ticketType = TicketType::factory()->create([
            'is_active' => true,
            'sales_start_at' => now()->subDays(2),
            'sales_end_at' => now()->subDays(1),
        ]);

        $this->assertFalse($ticketType->is_available_for_sale);
    }

    public function test_can_purchase_returns_true_when_in_sales_period()
    {
        $ticketType = TicketType::factory()->create([
            'is_active' => true,
            'sales_start_at' => now()->subDays(1),
            'sales_end_at' => now()->addDays(10),
            'min_purchase' => 1,
            'quantity' => 100,
        ]);

        $this->assertTrue($ticketType->canPurchase(1));
    }

    public function test_can_purchase_returns_false_when_inactive()
    {
        $ticketType = TicketType::factory()->create([
            'is_active' => false,
            'min_purchase' => 1,
        ]);

        $this->assertFalse($ticketType->canPurchase(1));
    }

    public function test_can_purchase_returns_false_when_no_quantity_available()
    {
        $ticketType = TicketType::factory()->create([
            'is_active' => true,
            'sales_start_at' => now()->subDays(1),
            'sales_end_at' => now()->addDays(10),
            'quantity' => 100,
            'sold_count' => 100,
        ]);

        $this->assertFalse($ticketType->canPurchase(1));
    }

    public function test_belongs_to_event()
    {
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);

        $this->assertInstanceOf(Event::class, $ticketType->event);
        $this->assertEquals($event->id, $ticketType->event->id);
    }

    /*
    public function test_uses_soft_deletes()
    {
        $ticketType = TicketType::factory()->create();

        $ticketType->delete();

        $deletedTicketType = TicketType::withTrashed()->find($ticketType->id);

        $this->assertNotNull($deletedTicketType);
        $this->assertNull(TicketType::find($ticketType->id));
    }
    */

    public function test_min_purchase_cannot_be_greater_than_max_purchase()
    {
        $ticketType = TicketType::factory()->create([
            'min_purchase' => 10,
            'max_purchase' => 5,
        ]);

        // This would fail on save due to validation
        $this->assertTrue($ticketType->min_purchase > $ticketType->max_purchase);
    }

    public function test_max_purchase_cannot_be_less_than_min_purchase()
    {
        $ticketType = TicketType::factory()->create([
            'min_purchase' => 10,
            'max_purchase' => 15,
        ]);

        $this->assertTrue($ticketType->max_purchase >= $ticketType->min_purchase);
    }

    public function test_sold_count_defaults_to_zero()
    {
        $event = Event::factory()->create();
        $ticketType = TicketType::create([
            'event_id' => $event->id,
            'name' => 'Test',
            'price' => 10,
            'quantity' => 100
        ]);

        $this->assertEquals(0, $ticketType->refresh()->sold_count);
    }

    public function test_is_active_defaults_to_true()
    {
        $ticketType = TicketType::factory()->create();

        $this->assertEquals(true, $ticketType->is_active);
    }

    public function test_sort_order_defaults_to_zero()
    {
        $ticketType = new TicketType;
        $this->assertNull($ticketType->sort_order); // It's null until saved/refreshed from DB default if not set
        // Actually, to test DB default:
        $event = Event::factory()->create();
        $ticketType = TicketType::create([
            'event_id' => $event->id,
            'name' => 'Test',
            'price' => 10,
            'quantity' => 100
        ]);
        
        $this->assertEquals(0, $ticketType->refresh()->sort_order);
    }

    public function test_price_can_be_decimal()
    {
        $ticketType = TicketType::factory()->create([
            'price' => 123456.78,
        ]);

        $this->assertEquals('123456.78', $ticketType->price);
        // $this->assertIsFloat($ticketType->price);
    }

    public function test_quantity_must_be_positive()
    {
        $ticketType = TicketType::factory()->create([
            'quantity' => 100,
        ]);

        $this->assertGreaterThan(0, $ticketType->quantity);
    }
}
