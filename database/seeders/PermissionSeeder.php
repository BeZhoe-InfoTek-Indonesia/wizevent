<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Event Management Permissions
        $eventPermissions = [
            'events.create',
            'events.edit',
            'events.delete',
            'events.publish',
            'events.cancel',
            'events.view',
        ];

        // Ticket Management Permissions
        $ticketPermissions = [
            'tickets.create',
            'tickets.edit',
            'tickets.delete',
            'tickets.view',
            'tickets.check-in',
        ];

        // User Management Permissions
        $userPermissions = [
            'users.view',
            'users.edit',
            'users.delete',
            'users.assign-roles',
            'users.manage-permissions',
        ];

        // Role Management Permissions
        $rolePermissions = [
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
        ];

        // Permission Management Permissions
        $permissionPermissions = [
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
        ];

        // Finance Management Permissions
        $financePermissions = [
            'finance.view-reports',
            'finance.verify-payments',
            'finance.process-refunds',
        ];

        // System Management Permissions
        $systemPermissions = [
            'system.manage-settings',
            'system.view-logs',
        ];

        // Combine all permissions
        $allPermissions = [
            ...$eventPermissions,
            ...$ticketPermissions,
            ...$userPermissions,
            ...$rolePermissions,
            ...$permissionPermissions,
            ...$financePermissions,
            ...$systemPermissions,
        ];

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Created '.count($allPermissions).' permissions across 7 categories:');
        $this->command->info('- Events: '.count($eventPermissions).' permissions');
        $this->command->info('- Tickets: '.count($ticketPermissions).' permissions');
        $this->command->info('- Users: '.count($userPermissions).' permissions');
        $this->command->info('- Roles: '.count($rolePermissions).' permissions');
        $this->command->info('- Permissions: '.count($permissionPermissions).' permissions');
        $this->command->info('- Finance: '.count($financePermissions).' permissions');
        $this->command->info('- System: '.count($systemPermissions).' permissions');
    }
}
