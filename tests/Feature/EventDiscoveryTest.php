<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_events_list_page_loads_successfully(): void
    {
        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertSee('Discover Events');
    }

    public function test_only_published_events_are_shown(): void
    {
        $publishedEvents = Event::factory()->count(3)->published()->create();
        Event::factory()->count(2)->draft()->create();
        Event::factory()->count(1)->cancelled()->create();

        $response = $this->get(route('events.index'));

        $response->assertStatus(200);

        foreach ($publishedEvents as $event) {
            $response->assertSee($event->title);
        }
    }

    public function test_search_filters_events_by_title(): void
    {
        Event::factory()->create(['title' => 'Tech Conference', 'status' => 'published', 'published_at' => now()]);
        Event::factory()->create(['title' => 'Music Festival', 'status' => 'published', 'published_at' => now()]);
        Event::factory()->create(['title' => 'Art Exhibition', 'status' => 'published', 'published_at' => now()]);

        $response = $this->get(route('events.index', ['search' => 'Tech']));

        $response->assertStatus(200);
        $response->assertSee('Tech Conference');
        $response->assertDontSee('Music Festival');
    }

    public function test_search_filters_events_by_location(): void
    {
        Event::factory()->create([
            'location' => 'New York, NY',
            'status' => 'published',
            'published_at' => now(),
        ]);
        Event::factory()->create([
            'location' => 'Los Angeles, CA',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('events.index', ['search' => 'New York']));

        $response->assertStatus(200);
        $response->assertSee('New York');
        $response->assertDontSee('Los Angeles');
    }

    public function test_category_filter_works(): void
    {
        $event1 = Event::factory()->published()->create();

        $response = $this->get(route('events.index', ['selectedCategory' => null]));

        $response->assertStatus(200);
        $response->assertSee($event1->title);
    }

    public function test_date_range_filter_works(): void
    {
        $eventThisWeek = Event::factory()->create([
            'event_date' => now()->addDays(2),
            'status' => 'published',
            'published_at' => now(),
        ]);
        $eventNextMonth = Event::factory()->create([
            'event_date' => now()->addMonth(),
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('events.index', ['dateRange' => 'this_week']));

        $response->assertStatus(200);
        $response->assertSee($eventThisWeek->title);
        $response->assertDontSee($eventNextMonth->title);
    }

    public function test_custom_date_range_filter_works(): void
    {
        $eventInRange = Event::factory()->create([
            'event_date' => now()->addDays(5),
            'status' => 'published',
            'published_at' => now(),
        ]);
        $eventOutOfRange = Event::factory()->create([
            'event_date' => now()->addMonth(),
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('events.index', [
            'dateRange' => 'custom',
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addWeek()->format('Y-m-d'),
        ]));

        $response->assertStatus(200);
        $response->assertSee($eventInRange->title);
        $response->assertDontSee($eventOutOfRange->title);
    }

    public function test_price_range_filter_works(): void
    {
        $event1 = Event::factory()->published()->create();
        TicketType::factory()->create([
            'event_id' => $event1->id,
            'price' => 10.00,
            'is_active' => true,
            'quantity' => 100,
        ]);

        $event2 = Event::factory()->published()->create();
        TicketType::factory()->create([
            'event_id' => $event2->id,
            'price' => 100.00,
            'is_active' => true,
            'quantity' => 100,
        ]);

        $response = $this->get(route('events.index', ['minPrice' => 0, 'maxPrice' => 50]));

        $response->assertStatus(200);
        $response->assertSee($event1->title);
        $response->assertDontSee($event2->title);
    }

    public function test_sorting_works(): void
    {
        $event1 = Event::factory()->create([
            'event_date' => now()->addWeek(),
            'status' => 'published',
            'published_at' => now(),
        ]);
        $event2 = Event::factory()->create([
            'event_date' => now()->addDay(),
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('events.index', ['sort' => 'date_asc']));
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertTrue(
            strpos($content, $event2->title) < strpos($content, $event1->title),
            'Event 2 should appear before Event 1 when sorting by date_asc'
        );

        $response = $this->get(route('events.index', ['sort' => 'date_desc']));
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertTrue(
            strpos($content, $event1->title) < strpos($content, $event2->title),
            'Event 1 should appear before Event 2 when sorting by date_desc'
        );
    }

    public function test_event_detail_page_loads_successfully(): void
    {
        $event = Event::factory()->published()->create();

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee($event->title);
    }

    public function test_event_detail_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get(route('events.show', 'invalid-slug'));

        $response->assertStatus(404);
    }

    public function test_event_detail_shows_correct_meta_tags(): void
    {
        $event = Event::factory()->published()->create([
            'title' => 'Test Event Title',
            'description' => 'This is a test description for the event',
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('Test Event Title');
        $response->assertSee('This is a test description for the event');
    }

    public function test_event_detail_shows_ticket_types(): void
    {
        $event = Event::factory()->published()->create();
        TicketType::factory()->create([
            'event_id' => $event->id,
            'name' => 'VIP Ticket',
            'price' => 100.00,
            'is_active' => true,
            'quantity' => 50,
            'sold_count' => 10,
        ]);
        TicketType::factory()->create([
            'event_id' => $event->id,
            'name' => 'General Admission',
            'price' => 50.00,
            'is_active' => true,
            'quantity' => 100,
            'sold_count' => 30,
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('VIP Ticket');
        $response->assertSee('General Admission');
        $response->assertSee('$100.00');
        $response->assertSee('$50.00');
    }

    public function test_sold_out_events_are_handled_correctly(): void
    {
        $event = Event::factory()->create([
            'status' => 'sold_out',
            'published_at' => now(),
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('Sold Out');
    }

    public function test_event_detail_shows_banner_image(): void
    {
        $event = Event::factory()->published()->create();

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('class="relative h-64', $content);
    }

    public function test_event_detail_shows_map_when_coordinates_available(): void
    {
        $event = Event::factory()->published()->create([
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee('maps.google.com');
    }

    public function test_pagination_works(): void
    {
        Event::factory()->count(15)->published()->create();

        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Showing', $content);
        $this->assertStringContainsString('events', $content);
    }

    public function test_url_parameters_are_preserved(): void
    {
        Event::factory()->count(15)->published()->create();

        $response = $this->get(route('events.index', [
            'search' => 'test',
            'selectedCategory' => 1,
            'sort' => 'date_desc',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Discover Events');
    }
}
