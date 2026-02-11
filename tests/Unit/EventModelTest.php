<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\TicketType;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_event_has_correct_fillable_fields()
    {
        $event = new Event;
        $fillable = $event->getFillable();

        $expectedFillable = [
            'title', 'slug', 'description', 'event_date', 'event_end_date',
            'location', 'venue_name', 'latitude', 'longitude', 'google_place_id',
            'banner_image', 'status', 'published_at',
            'sales_start_at', 'sales_end_at', 'seating_enabled', 'total_capacity',
            'cancellation_reason', 'category_id', 'created_by', 'updated_by',
        ];

        foreach ($expectedFillable as $field) {
            $this->assertContains($field, $fillable);
        }
    }

    public function test_event_status_defaults_to_draft()
    {
        $event = Event::factory()->create();

        $this->assertEquals('draft', $event->status);
    }

    public function test_event_can_be_published_with_complete_data()
    {
        $category = EventCategory::factory()->create();

        $event = Event::factory()->create([
            'status' => 'draft',
            'title' => 'Valid Event Title',
            'description' => 'This is a valid event description that meets the minimum length requirements.',
            'event_date' => now()->addWeeks(2),
            'location' => 'Test Location',
            'banner_image' => 'banner.jpg',
            'category_id' => $category->id,
        ]);

        TicketType::factory()->create([
            'event_id' => $event->id,
            'is_active' => true,
        ]);

        $this->assertTrue($event->canBePublished());
    }

    public function test_event_cannot_be_published_without_title()
    {
        $event = Event::factory()->create(['title' => '']);

        $this->assertFalse($event->canBePublished());
    }

    public function test_event_cannot_be_published_without_description()
    {
        $event = Event::factory()->create(['description' => 'Too short']);

        $this->assertFalse($event->canBePublished());
    }

    public function test_event_cannot_be_published_without_event_date()
    {
        $event = Event::factory()->create(['event_date' => null]);

        $this->assertFalse($event->canBePublished());
    }

    public function test_event_cannot_be_published_without_location()
    {
        $event = Event::factory()->create(['location' => '']);

        $this->assertFalse($event->canBePublished());
    }

    public function test_event_cannot_be_published_without_banner_image()
    {
        $event = Event::factory()->create(['banner_image' => null]);

        $this->assertFalse($event->canBePublished());
    }

    public function test_event_cannot_be_published_without_active_ticket_types()
    {
        $event = Event::factory()->create(['status' => 'draft']);

        TicketType::factory()->create([
            'event_id' => $event->id,
            'is_active' => false,
        ]);

        $this->assertFalse($event->canBePublished());
    }

    public function test_published_event_cannot_be_published_again()
    {
        $event = Event::factory()->published()->create();

        $this->assertFalse($event->canBePublished());
    }

    public function test_event_can_be_cancelled_when_published()
    {
        $event = Event::factory()->published()->create();

        $this->assertTrue($event->canBeCancelled());
    }

    public function test_draft_event_cannot_be_cancelled()
    {
        $event = Event::factory()->draft()->create();

        $this->assertFalse($event->canBeCancelled());
    }

    public function test_event_can_be_deleted_when_draft()
    {
        $event = Event::factory()->draft()->create();

        $this->assertTrue($event->canBeDeleted());
    }

    public function test_published_event_cannot_be_deleted()
    {
        $event = Event::factory()->published()->create();

        $this->assertFalse($event->canBeDeleted());
    }

    public function test_sold_out_event_cannot_be_deleted()
    {
        $event = Event::factory()->create([
            'status' => 'sold_out',
        ]);

        $this->assertFalse($event->canBeDeleted());
    }

    public function test_cancelled_event_cannot_be_deleted()
    {
        $event = Event::factory()->create([
            'status' => 'cancelled',
        ]);

        $this->assertFalse($event->canBeDeleted());
    }

    public function test_event_has_many_ticket_types()
    {
        $event = Event::factory()->create();

        TicketType::factory()->count(3)->create([
            'event_id' => $event->id,
        ]);

        $this->assertCount(3, $event->ticketTypes);
    }

    public function test_event_belongs_to_category()
    {
        $event = Event::factory()->create();

        $this->assertInstanceOf(EventCategory::class, $event->category);
    }

    public function test_event_belongs_to_creator()
    {
        $creator = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $creator->id]);

        $this->assertEquals($creator->id, $event->created_by);
    }

    public function test_event_has_activity_logging()
    {
        $event = Event::factory()->create();

        $this->assertIsObject($event->getActivitylogOptions());
    }

    public function test_event_uses_soft_deletes()
    {
        $event = Event::factory()->create();

        $event->delete();

        $deletedEvent = Event::withTrashed()->find($event->id);

        $this->assertNotNull($deletedEvent);
        $this->assertNull(Event::find($event->id));
    }

    public function test_event_status_can_be_updated()
    {
        $event = Event::factory()->draft()->create();

        $event->update(['status' => 'published']);

        $this->assertEquals('published', $event->fresh()->status);
    }

    public function test_published_at_is_set_when_published()
    {
        $event = Event::factory()->create();

        $this->assertNull($event->published_at);

        $event->update(['status' => 'published', 'published_at' => now()]);

        $this->assertNotNull($event->fresh()->published_at);
        $this->assertEquals(now()->format('Y-m-d H:i:s'), $event->fresh()->published_at->format('Y-m-d H:i:s'));
    }

    public function test_cancellation_reason_is_set_when_cancelled()
    {
        $event = Event::factory()->create();

        $this->assertNull($event->cancellation_reason);

        $event->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Event cancelled due to weather',
        ]);

        $this->assertEquals('cancelled', $event->fresh()->status);
        $this->assertEquals('Event cancelled due to weather', $event->fresh()->cancellation_reason);
    }

    public function test_published_scope_filters_published_events()
    {
        Event::factory()->create(['status' => 'published']);
        Event::factory()->create(['status' => 'draft']);
        Event::factory()->create(['status' => 'cancelled']);

        $publishedEvents = Event::published()->get();

        $this->assertCount(1, $publishedEvents);
        $this->assertEquals('published', $publishedEvents->first()->status);
    }

    public function test_draft_scope_filters_draft_events()
    {
        Event::factory()->create(['status' => 'published']);
        Event::factory()->create(['status' => 'draft']);
        Event::factory()->create(['status' => 'cancelled']);

        $draftEvents = Event::draft()->get();

        $this->assertCount(1, $draftEvents);
        $this->assertEquals('draft', $draftEvents->first()->status);
    }

    public function test_upcoming_scope_filters_future_published_events()
    {
        Event::factory()->create([
            'status' => 'published',
            'event_date' => now()->addDays(30),
        ]);

        Event::factory()->create([
            'status' => 'published',
            'event_date' => now()->subDays(10),
        ]);

        Event::factory()->create([
            'status' => 'published',
            'event_date' => now()->subDays(30),
        ]);

        $upcomingEvents = Event::upcoming()->get();

        $this->assertCount(1, $upcomingEvents);
    }

    public function test_past_scope_filters_past_events()
    {
        Event::factory()->create([
            'status' => 'published',
            'event_date' => now()->subDays(10),
        ]);

        Event::factory()->create([
            'status' => 'published',
            'event_date' => now()->addDays(30),
        ]);

        Event::factory()->create([
            'status' => 'published',
            'event_date' => now()->addDays(30),
        ]);

        $pastEvents = Event::past()->get();

        $this->assertCount(3, $pastEvents);
    }

    public function test_event_calculates_available_tickets()
    {
        $event = Event::factory()->create();

        TicketType::factory()->count(3)->create([
            'event_id' => $event->id,
            'quantity' => 100,
            'sold_count' => 30,
        ]);

        $this->assertEquals(210, $event->available_tickets);
    }

    public function test_event_calculates_sold_tickets()
    {
        $event = Event::factory()->create();

        $soldCounts = [10, 20, 30];
        foreach ($soldCounts as $soldCount) {
            TicketType::factory()->create([
                'event_id' => $event->id,
                'sold_count' => $soldCount,
            ]);
        }

        $this->assertEquals(60, $event->sold_tickets);
    }

    public function test_event_has_relationship_with_user()
    {
        $creator = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $creator->id]);

        $this->assertNotNull($event->created_by);
        $this->assertInstanceOf(User::class, $event->creator);
    }

    public function test_event_can_have_category_null()
    {
        $event = Event::factory()->create(['category_id' => null]);

        $this->assertNull($event->category);
    }

    public function test_soft_delete_preserves_event_data()
    {
        $event = Event::factory()->create();
        $eventId = $event->id;

        $event->delete();

        $deletedEvent = Event::withTrashed()->find($eventId);

        $this->assertNotNull($deletedEvent);
        $this->assertEquals($event->title, $deletedEvent->title);
        $this->assertEquals($event->description, $deletedEvent->description);
        $this->assertEquals($event->created_by, $deletedEvent->created_by);
        $this->assertNotNull($deletedEvent->deleted_at);
    }

    public function test_event_can_be_restored_after_soft_delete()
    {
        $event = Event::factory()->create();
        $eventId = $event->id;

        $event->delete();

        Event::withTrashed()->find($eventId)->restore();

        $restoredEvent = Event::find($eventId);

        $this->assertNotNull($restoredEvent);
        $this->assertEquals($event->title, $restoredEvent->title);
        $this->assertEquals($event->created_by, $restoredEvent->created_by);
    }

    public function test_multiple_events_can_be_created()
    {
        Event::factory()->count(5)->create();

        $this->assertCount(5, Event::all());
    }

    public function test_event_relationships_are_loaded_efficiently()
    {
        $event = Event::factory()->create();

        TicketType::factory()->count(3)->create([
            'event_id' => $event->id,
        ]);

        $this->assertCount(3, $event->ticketTypes);
        $this->assertCount(3, $event->ticketTypes()->get());
    }
}
