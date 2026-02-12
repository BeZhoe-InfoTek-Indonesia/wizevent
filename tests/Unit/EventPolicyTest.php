<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\TicketType;
use App\Models\User;
use App\Policies\EventPolicy;
use Database\Seeders\RolePermissionSeeder;
use Tests\TestCase;

class EventPolicyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_super_admin_can_view_any_event()
    {
        $superAdmin = User::where('email', 'admin@eventmanagement.com')->first();
        $policy = new EventPolicy;

        $this->assertTrue($policy->viewAny($superAdmin));
    }

    public function test_event_manager_can_view_any_event()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');
        $policy = new EventPolicy;

        $this->assertTrue($policy->viewAny($eventManager));
    }

    public function test_user_can_view_event_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.view');
        $event = Event::factory()->create(['created_by' => User::factory()->create()->id]);
        $policy = new EventPolicy;

        $this->assertTrue($policy->view($user, $event));
    }

    public function test_event_manager_can_view_own_event()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');
        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $policy = new EventPolicy;

        $this->assertTrue($policy->view($eventManager, $event));
    }

    public function test_event_manager_cannot_view_others_event_without_permission()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');
        $otherUser = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $otherUser->id]);
        $policy = new EventPolicy;

        $this->assertFalse($policy->view($eventManager, $event));
    }

    public function test_user_can_create_event_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.create');
        $policy = new EventPolicy;

        $this->assertTrue($policy->create($user));
    }

    public function test_user_without_permission_cannot_create_event()
    {
        $user = User::factory()->create();
        $policy = new EventPolicy;

        $this->assertFalse($policy->create($user));
    }

    public function test_user_can_update_event_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.edit');
        $event = Event::factory()->create(['created_by' => User::factory()->create()->id]);
        $policy = new EventPolicy;

        $this->assertTrue($policy->update($user, $event));
    }

    public function test_event_manager_can_update_own_event()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');
        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $policy = new EventPolicy;

        $this->assertTrue($policy->update($eventManager, $event));
    }

    public function test_event_manager_cannot_update_others_event_without_permission()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');
        $otherUser = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $otherUser->id]);
        $policy = new EventPolicy;

        $this->assertFalse($policy->update($eventManager, $event));
    }

    public function test_super_admin_can_delete_any_event()
    {
        $superAdmin = User::where('email', 'admin@eventmanagement.com')->first();
        $event = Event::factory()->create(['status' => 'draft']);
        $policy = new EventPolicy;

        $this->assertTrue($policy->delete($superAdmin, $event));
    }

    public function test_user_cannot_delete_published_event()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.delete');
        $event = Event::factory()->create(['status' => 'published']);
        $policy = new EventPolicy;

        $this->assertFalse($policy->delete($user, $event));
    }

    public function test_user_can_delete_draft_event_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.delete');
        $event = Event::factory()->create(['status' => 'draft']);
        $policy = new EventPolicy;

        $this->assertTrue($policy->delete($user, $event));
    }

    public function test_user_can_publish_event_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.publish');

        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'status' => 'draft',
            'category_id' => $category->id,
        ]);

        TicketType::factory()->create([
            'event_id' => $event->id,
            'is_active' => true,
        ]);

        $policy = new EventPolicy;

        $this->assertTrue($policy->publish($user, $event));
    }

    public function test_user_cannot_publish_incomplete_event()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.publish');
        $event = Event::factory()->create([
            'status' => 'draft',
            'description' => 'Too short',
        ]);
        $policy = new EventPolicy;

        $this->assertFalse($policy->publish($user, $event));
    }

    public function test_user_can_cancel_published_event_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.cancel');
        $event = Event::factory()->create(['status' => 'published']);
        $policy = new EventPolicy;

        $this->assertTrue($policy->cancel($user, $event));
    }

    public function test_user_cannot_cancel_draft_event()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('events.cancel');
        $event = Event::factory()->create(['status' => 'draft']);
        $policy = new EventPolicy;

        $this->assertFalse($policy->cancel($user, $event));
    }

    public function test_event_manager_can_publish_own_event()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'status' => 'draft',
            'category_id' => $category->id,
            'created_by' => $eventManager->id,
        ]);

        TicketType::factory()->create([
            'event_id' => $event->id,
            'is_active' => true,
        ]);

        $policy = new EventPolicy;

        $this->assertTrue($policy->publish($eventManager, $event));
    }

    public function test_event_manager_can_cancel_own_event()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');
        $event = Event::factory()->create([
            'status' => 'published',
            'created_by' => $eventManager->id,
        ]);
        $policy = new EventPolicy;

        $this->assertTrue($policy->cancel($eventManager, $event));
    }
}
