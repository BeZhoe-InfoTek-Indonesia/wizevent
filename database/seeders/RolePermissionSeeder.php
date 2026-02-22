<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call PermissionSeeder first to ensure all permissions exist
        $this->call(PermissionSeeder::class);

        // Create roles and assign comprehensive permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $eventManager = Role::firstOrCreate(['name' => 'Event Manager']);
        $financeAdmin = Role::firstOrCreate(['name' => 'Finance Admin']);
        $checkInStaff = Role::firstOrCreate(['name' => 'Check-in Staff']);
        $visitor = Role::firstOrCreate(['name' => 'Visitor']);

        // Super Admin gets all permissions
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);

        // Event Manager permissions - can manage events and related tickets
        $eventManager->givePermissionTo([
            'events.create',
            'events.edit',
            'events.publish',
            'events.view',
            'tickets.create',
            'tickets.edit',
            'tickets.view',
            'tickets.check-in',
            'users.view',
            'organizers.view',
            'organizers.create',
            'organizers.edit',
            'performers.view',
            'performers.create',
            'performers.edit',
            'testimonials.view',
            'testimonials.moderate',
            'testimonials.publish',
            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',
            'faqs.view',
            'faqs.create',
            'faqs.edit',
            'faqs.delete',
            'email-templates.view',
            'email-templates.create',
            'email-templates.edit',
            'email-templates.delete',
        ]);

        // Finance Admin permissions - can manage financial aspects
        $financeAdmin->givePermissionTo([
            'finance.view-reports',
            'finance.verify-payments',
            'finance.process-refunds',
            'events.view',
            'tickets.view',
            'orders.view',
            'payment-proofs.view',
            'payment-proofs.verify',
            'payment-proofs.approve',
            'payment-proofs.reject',
        ]);

        // Check-in Staff permissions - can check in tickets and view events
        $checkInStaff->givePermissionTo([
            'tickets.check-in',
            'tickets.view',
            'events.view',
        ]);

        // Visitor permissions - can book and view tickets
        $visitor->givePermissionTo([
            'tickets.create',
            'tickets.view',
            'events.view',
        ]);

        // Create default Super Admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@eventmanagement.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );

        $user->assignRole('Super Admin');

        $this->command->info('Assigned permissions to roles:');
        $this->command->info('- Super Admin: All permissions ('.$superAdmin->permissions->count().')');
        $this->command->info('- Event Manager: '.$eventManager->permissions->count().' permissions');
        $this->command->info('- Finance Admin: '.$financeAdmin->permissions->count().' permissions');
        $this->command->info('- Check-in Staff: '.$checkInStaff->permissions->count().' permissions');
        $this->command->info('- Visitor: '.$visitor->permissions->count().' permissions');
    }
}
