<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use App\Policies\TicketTypePolicy;
use Database\Seeders\RolePermissionSeeder;
use Tests\TestCase;

class TicketTypePolicyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_super_admin_can_view_any_ticket_type()
    {
        $superAdmin = User::where('email', 'admin@eventmanagement.com')->first();
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->viewAny($superAdmin));
    }

    public function test_event_manager_can_view_any_ticket_type()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->viewAny($eventManager));
    }

    public function test_user_can_view_ticket_type_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('tickets.view');

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->view($user, $ticketType));
    }

    public function test_event_manager_can_view_own_event_ticket_types()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->view($eventManager, $ticketType));
    }

    public function test_event_manager_cannot_view_others_event_ticket_types()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $otherUser = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $otherUser->id]);
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertFalse($policy->view($eventManager, $ticketType));
    }

    public function test_user_can_create_ticket_type_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('tickets.create');
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->create($user));
    }

    public function test_user_without_permission_cannot_create_ticket_type()
    {
        $user = User::factory()->create();
        $policy = new TicketTypePolicy;

        $this->assertFalse($policy->create($user));
    }

    public function test_user_can_update_ticket_type_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('tickets.edit');

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->update($user, $ticketType));
    }

    public function test_event_manager_can_update_own_event_ticket_types()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->update($eventManager, $ticketType));
    }

    public function test_event_manager_cannot_update_others_event_ticket_types()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $otherUser = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $otherUser->id]);
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertFalse($policy->update($eventManager, $ticketType));
    }

    public function test_super_admin_can_delete_any_ticket_type()
    {
        $superAdmin = User::where('email', 'admin@eventmanagement.com')->first();

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'sold_count' => 5,
        ]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->delete($superAdmin, $ticketType));
    }

    public function test_user_cannot_delete_ticket_type_with_sales()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('tickets.delete');

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'sold_count' => 10,
        ]);
        $policy = new TicketTypePolicy;

        $this->assertFalse($policy->delete($user, $ticketType));
    }

    public function test_user_can_delete_ticket_type_without_sales()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('tickets.delete');

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'sold_count' => 0,
        ]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->delete($user, $ticketType));
    }

    public function test_event_manager_can_delete_own_event_ticket_types()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'sold_count' => 0,
        ]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->delete($eventManager, $ticketType));
    }

    public function test_event_manager_cannot_delete_sold_ticket_types_of_own_event()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $ticketType = TicketType::factory()->create([
            'event_id' => $event->id,
            'sold_count' => 5,
        ]);
        $policy = new TicketTypePolicy;

        $this->assertFalse($policy->delete($eventManager, $ticketType));
    }

    public function test_super_admin_can_force_delete_any_ticket_type()
    {
        $superAdmin = User::where('email', 'admin@eventmanagement.com')->first();

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertTrue($policy->forceDelete($superAdmin, $ticketType));
    }

    public function test_non_super_admin_cannot_force_delete_ticket_type()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $this->assertFalse($policy->forceDelete($eventManager, $ticketType));
    }

    public function test_owns_event_helper_returns_true_for_super_admin()
    {
        $superAdmin = User::where('email', 'admin@eventmanagement.com')->first();
        $policy = new TicketTypePolicy;

        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);

        $reflection = new \ReflectionClass($policy);
        $method = $reflection->getMethod('ownsEvent');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($policy, $superAdmin, $ticketType));
    }

    public function test_owns_event_helper_returns_true_for_event_creator()
    {
        $eventManager = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $eventManager->id]);
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $reflection = new \ReflectionClass($policy);
        $method = $reflection->getMethod('ownsEvent');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($policy, $eventManager, $ticketType));
    }

    public function test_owns_event_helper_returns_false_for_non_creator()
    {
        $eventManager = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $otherUser->id]);
        $ticketType = TicketType::factory()->create(['event_id' => $event->id]);
        $policy = new TicketTypePolicy;

        $reflection = new \ReflectionClass($policy);
        $method = $reflection->getMethod('ownsEvent');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($policy, $eventManager, $ticketType));
    }
}
