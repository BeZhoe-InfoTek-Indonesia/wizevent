<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingComponent;
use Illuminate\Database\Seeder;

/**
 * Setting Component Seeder
 *
 * Creates master data for event categories, tags, and ticket types.
 * These are used as dropdown options in the Event creation form.
 */
class SettingComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Setting Components...');

        // Event Categories
        $this->seedEventCategories();

        // Event Tags
        $this->seedEventTags();

        // Ticket Types
        $this->seedTicketTypes();

        $this->command->info('✓ Setting Components seeded successfully!');
    }

    /**
     * Seed event categories.
     */
    private function seedEventCategories(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'event_categories'],
            [
                'name' => 'Event Categories',
            ]
        );

        $categories = [
            'Music & Concerts',
            'Technology & Conferences',
            'Food & Beverage',
            'Sports & Fitness',
            'Arts & Culture',
            'Business & Networking',
            'Education & Workshops',
            'Family & Kids',
            'Health & Wellness',
            'Entertainment & Shows',
        ];

        foreach ($categories as $category) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $category,
                ],
                [
                    'type' => 'string',
                    'value' => $category,
                ]
            );
        }

        $this->command->info('  ✓ Event Categories seeded');
    }

    /**
     * Seed event tags.
     */
    private function seedEventTags(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'event_tags'],
            [
                'name' => 'Event Tags',
            ]
        );

        $tags = [
            'Indoor',
            'Outdoor',
            'Free Entry',
            'Paid Event',
            'Family Friendly',
            '18+ Only',
            'VIP Available',
            'Early Bird',
            'Workshop',
            'Networking',
            'Live Performance',
            'Exhibition',
            'Food & Drinks',
            'Parking Available',
            'Accessible',
            'Pet Friendly',
            'Alcohol Served',
            'Cashless',
            'Multi-day',
            'Virtual',
        ];

        foreach ($tags as $tag) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $tag,
                ],
                [
                    'type' => 'string',
                    'value' => $tag,
                ]
            );
        }

        $this->command->info('  ✓ Event Tags seeded');
    }

    /**
     * Seed ticket types.
     */
    private function seedTicketTypes(): void
    {
        $setting = Setting::firstOrCreate(
            ['key' => 'ticket_types'],
            [
                'name' => 'Ticket Types',
            ]
        );

        $ticketTypes = [
            'Regular',
            'VIP',
            'Early Bird',
            'Student',
            'Senior',
            'Group',
            'Corporate',
            'Premium',
            'Standard',
            'Economy',
            'Backstage',
            'All Access',
            'Day Pass',
            'Weekend Pass',
            'Season Pass',
        ];

        foreach ($ticketTypes as $ticketType) {
            SettingComponent::firstOrCreate(
                [
                    'setting_id' => $setting->id,
                    'name' => $ticketType,
                ],
                [
                    'type' => 'string',
                    'value' => $ticketType,
                ]
            );
        }

        $this->command->info('  ✓ Ticket Types seeded');
    }
}
