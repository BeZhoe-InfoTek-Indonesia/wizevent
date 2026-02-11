<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_super_admin_has_all_permissions()
    {
        $superAdmin = User::where('email', 'admin@eventmanagement.com')->first();

        $this->assertTrue($superAdmin->hasRole('Super Admin'));
        $this->assertEquals(20, $superAdmin->getAllPermissions()->count());
    }

    public function test_event_manager_permissions()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $expectedPermissions = [
            'events.create',
            'events.edit',
            'events.publish',
            'events.view',
            'tickets.create',
            'tickets.edit',
            'tickets.view',
            'tickets.check-in',
        ];

        foreach ($expectedPermissions as $permission) {
            $this->assertTrue($eventManager->hasPermissionTo($permission), "Missing permission: {$permission}");
        }

        // Should not have finance permissions
        $this->assertFalse($eventManager->hasPermissionTo('finance.view-reports'));
        $this->assertFalse($eventManager->hasPermissionTo('users.edit'));
    }

    public function test_finance_admin_permissions()
    {
        $financeAdmin = User::factory()->create();
        $financeAdmin->assignRole('Finance Admin');

        $this->assertTrue($financeAdmin->hasPermissionTo('finance.view-reports'));
        $this->assertTrue($financeAdmin->hasPermissionTo('finance.verify-payments'));
        $this->assertTrue($financeAdmin->hasPermissionTo('finance.process-refunds'));

        // Should not have event management permissions
        $this->assertFalse($financeAdmin->hasPermissionTo('events.create'));
        $this->assertFalse($financeAdmin->hasPermissionTo('events.edit'));
    }

    public function test_permission_helper_functionality()
    {
        $eventManager = User::factory()->create();
        $eventManager->assignRole('Event Manager');

        $this->actingAs($eventManager);

        $this->assertTrue(\App\Services\PermissionHelper::canManageEvents());
        $this->assertTrue(\App\Services\PermissionHelper::canManageTickets());
        $this->assertFalse(\App\Services\PermissionHelper::canManageUsers());
        $this->assertFalse(\App\Services\PermissionHelper::canAccessFinance());
    }
}
