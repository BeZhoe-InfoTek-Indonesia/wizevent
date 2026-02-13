<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Favorite;
use App\Models\Testimonial;
use App\Models\TestimonialVote;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialEngagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_can_favorite_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);

        $this->actingAs($user)
            ->post(route('events.favorite', $event->slug));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_user_can_unfavorite_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);
        $favorite = Favorite::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $this->actingAs($user)
            ->post(route('events.favorite', $event->slug));

        $this->assertDatabaseMissing('favorites', [
            'id' => $favorite->id,
        ]);
    }

    public function test_guest_cannot_favorite_event()
    {
        $event = Event::factory()->create(['status' => 'published']);

        $this->post(route('events.favorite', $event->slug))
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('favorites', [
            'event_id' => $event->id,
        ]);
    }

    public function test_user_can_view_loved_events()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);
        Favorite::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard.loved-events'));

        $response->assertStatus(200);
        $response->assertSee($event->title);
    }

    public function test_user_can_remove_favorite_from_dashboard()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);
        $favorite = Favorite::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $this->actingAs($user)
            ->post(route('favorites.remove', $event->id));

        $this->assertDatabaseMissing('favorites', [
            'id' => $favorite->id,
        ]);
    }

    public function test_user_with_ticket_can_submit_testimonial()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'quantity' => 10,
        ]);

        $order = \App\Models\Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'completed',
        ]);

        $orderItem = \App\Models\OrderItem::factory()->create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
        ]);

        $ticket = Ticket::factory()->create([
            'order_item_id' => $orderItem->id,
            'ticket_type_id' => $ticketType->id,
            'status' => 'used',
        ]);

        $this->actingAs($user)
            ->post(route('testimonials.store', $event->slug), [
                'content' => 'Great event!',
                'rating' => 5,
            ]);

        $this->assertDatabaseHas('testimonials', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'content' => 'Great event!',
            'rating' => 5,
            'status' => 'pending',
        ]);
    }

    public function test_user_without_ticket_cannot_submit_testimonial()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);

        $this->actingAs($user)
            ->post(route('testimonials.store', $event->slug), [
                'content' => 'Great event!',
                'rating' => 5,
            ])
            ->assertStatus(403);

        $this->assertDatabaseMissing('testimonials', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_user_cannot_submit_duplicate_testimonial()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);
        Testimonial::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'approved',
        ]);

        $this->actingAs($user)
            ->post(route('testimonials.store', $event->slug), [
                'content' => 'Great event!',
                'rating' => 5,
            ])
            ->assertStatus(403);

        $this->assertEquals(1, Testimonial::where('user_id', $user->id)
            ->where('event_id', $event->id)->count());
    }

    public function test_guest_cannot_submit_testimonial()
    {
        $event = Event::factory()->create(['status' => 'published']);

        $this->post(route('testimonials.store', $event->slug), [
            'content' => 'Great event!',
            'rating' => 5,
        ])
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('testimonials', [
            'event_id' => $event->id,
        ]);
    }

    public function test_user_can_vote_on_testimonial()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);
        $testimonial = Testimonial::factory()->create([
            'event_id' => $event->id,
            'status' => 'approved',
        ]);

        $this->actingAs($user)
            ->post(route('testimonials.vote', $testimonial->id), [
                'is_helpful' => true,
            ]);

        $this->assertDatabaseHas('testimonial_votes', [
            'user_id' => $user->id,
            'testimonial_id' => $testimonial->id,
            'is_helpful' => true,
        ]);
    }

    public function test_user_cannot_vote_twice_on_same_testimonial()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['status' => 'published']);
        $testimonial = Testimonial::factory()->create([
            'event_id' => $event->id,
            'status' => 'approved',
        ]);

        TestimonialVote::factory()->create([
            'user_id' => $user->id,
            'testimonial_id' => $testimonial->id,
            'is_helpful' => true,
        ]);

        $this->actingAs($user)
            ->post(route('testimonials.vote', $testimonial->id), [
                'is_helpful' => false,
            ])
            ->assertStatus(403);

        $this->assertEquals(1, TestimonialVote::where('user_id', $user->id)
            ->where('testimonial_id', $testimonial->id)->count());
    }

    public function test_guest_cannot_vote_on_testimonial()
    {
        $event = Event::factory()->create(['status' => 'published']);
        $testimonial = Testimonial::factory()->create([
            'event_id' => $event->id,
            'status' => 'approved',
        ]);

        $this->post(route('testimonials.vote', $testimonial->id), [
            'is_helpful' => true,
        ])
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('testimonial_votes', [
            'testimonial_id' => $testimonial->id,
        ]);
    }

    public function test_approved_testimonials_are_visible_on_event_page()
    {
        $event = Event::factory()->create(['status' => 'published']);
        $approvedTestimonial = Testimonial::factory()->create([
            'event_id' => $event->id,
            'status' => 'approved',
        ]);
        $pendingTestimonial = Testimonial::factory()->create([
            'event_id' => $event->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertSee($approvedTestimonial->content);
        $response->assertDontSee($pendingTestimonial->content);
    }

    public function test_event_detail_page_shows_share_buttons()
    {
        $event = Event::factory()->create(['status' => 'published']);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertSeeText('Share Event');
        $response->assertSee('twitter.com/intent/tweet');
        $response->assertSee('facebook.com/sharer');
    }

    public function test_event_detail_page_shows_calendar_button()
    {
        $event = Event::factory()->create(['status' => 'published']);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertSee('calendar');
    }
}
