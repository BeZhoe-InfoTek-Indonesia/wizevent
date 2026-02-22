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

        // Setting Component Management Permissions
        $settingComponentPermissions = [
            'setting-components.view',
            'setting-components.create',
            'setting-components.edit',
            'setting-components.delete',
        ];

        // Event Planner Permissions
        $eventPlannerPermissions = [
            'event-plans.view',
            'event-plans.create',
            'event-plans.edit',
            'event-plans.delete',
        ];

        // CMS Management Permissions
        $cmsPermissions = [
            'cms.view',
            'cms.create',
            'cms.edit',
            'cms.delete',
        ];

        // Banner Management Permissions
        $bannerPermissions = [
            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',
        ];

        // FAQ Management Permissions
        $faqPermissions = [
            'faqs.view',
            'faqs.create',
            'faqs.edit',
            'faqs.delete',
        ];

        // CMS Page Management Permissions
        $cmsPagePermissions = [
            'cms-pages.view',
            'cms-pages.create',
            'cms-pages.edit',
            'cms-pages.delete',
        ];

        // Payment Bank Management Permissions
        $paymentBankPermissions = [
            'payment-banks.view',
            'payment-banks.create',
            'payment-banks.edit',
            'payment-banks.delete',
        ];

        // Email Template Management Permissions
        $emailTemplatePermissions = [
            'email-templates.view',
            'email-templates.create',
            'email-templates.edit',
            'email-templates.delete',
        ];

        // Promo Countdown Management Permissions
        $promoCountdownPermissions = [
            'promo-countdowns.view',
            'promo-countdowns.create',
            'promo-countdowns.edit',
            'promo-countdowns.delete',
        ];

        // Organizer Management Permissions
        $organizerPermissions = [
            'organizers.view',
            'organizers.create',
            'organizers.edit',
            'organizers.delete',
        ];

        // Performer Management Permissions
        $performerPermissions = [
            'performers.view',
            'performers.create',
            'performers.edit',
            'performers.delete',
        ];

        // Testimonial Management Permissions
        $testimonialPermissions = [
            'testimonials.view',
            'testimonials.create',
            'testimonials.edit',
            'testimonials.delete',
            'testimonials.moderate',
            'testimonials.publish',
        ];

        // Order Management Permissions
        $orderPermissions = [
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
        ];

        // Payment Proof Management Permissions
        $paymentProofPermissions = [
            'payment-proofs.view',
            'payment-proofs.create',
            'payment-proofs.edit',
            'payment-proofs.delete',
            'payment-proofs.verify',
            'payment-proofs.approve',
            'payment-proofs.reject',
        ];

        // Activity Log Management Permissions
        $activityPermissions = [
            'activities.view',
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
            ...$settingComponentPermissions,
            ...$eventPlannerPermissions,
            ...$cmsPermissions,
            ...$bannerPermissions,
            ...$faqPermissions,
            ...$cmsPagePermissions,
            ...$paymentBankPermissions,
            ...$emailTemplatePermissions,
            ...$promoCountdownPermissions,
            ...$organizerPermissions,
            ...$performerPermissions,
            ...$testimonialPermissions,
            ...$orderPermissions,
            ...$paymentProofPermissions,
            ...$activityPermissions,
        ];

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Created '.count($allPermissions).' permissions across 18 categories:');
        $this->command->info('- Events: '.count($eventPermissions).' permissions');
        $this->command->info('- Tickets: '.count($ticketPermissions).' permissions');
        $this->command->info('- Users: '.count($userPermissions).' permissions');
        $this->command->info('- Roles: '.count($rolePermissions).' permissions');
        $this->command->info('- Permissions: '.count($permissionPermissions).' permissions');
        $this->command->info('- Finance: '.count($financePermissions).' permissions');
        $this->command->info('- System: '.count($systemPermissions).' permissions');
        $this->command->info('- Setting Components: '.count($settingComponentPermissions).' permissions');
        $this->command->info('- Event Planner: '.count($eventPlannerPermissions).' permissions');
        $this->command->info('- CMS: '.count($cmsPermissions).' permissions');
        $this->command->info('- Banners: '.count($bannerPermissions).' permissions');
        $this->command->info('- FAQs: '.count($faqPermissions).' permissions');
        $this->command->info('- CMS Pages: '.count($cmsPagePermissions).' permissions');
        $this->command->info('- Payment Banks: '.count($paymentBankPermissions).' permissions');
        $this->command->info('- Email Templates: '.count($emailTemplatePermissions).' permissions');
        $this->command->info('- Promo Countdowns: '.count($promoCountdownPermissions).' permissions');
        $this->command->info('- Organizers: '.count($organizerPermissions).' permissions');
        $this->command->info('- Performers: '.count($performerPermissions).' permissions');
        $this->command->info('- Testimonials: '.count($testimonialPermissions).' permissions');
        $this->command->info('- Orders: '.count($orderPermissions).' permissions');
        $this->command->info('- Payment Proofs: '.count($paymentProofPermissions).' permissions');
        $this->command->info('- Activities: '.count($activityPermissions).' permissions');
    }
}
