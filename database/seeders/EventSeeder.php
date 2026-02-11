<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Event Seeder
 *
 * Creates sample events with ticket types for testing.
 */
class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if (! $admin) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $events = [
            [
                'title' => 'Music Festival 2026',
                'description' => 'Join us for the biggest music festival of the year featuring top artists from around the world. Experience three days of amazing music, food, and entertainment.',
                'event_date' => now()->addDays(30),
                'event_end_date' => now()->addDays(32),
                'location' => 'Jakarta Convention Center',
                'venue_name' => 'Plenary Hall',
                'latitude' => -6.2250,
                'longitude' => 106.8000,
                'status' => 'published',
                'published_at' => now(),
                'sales_start_at' => now(),
                'sales_end_at' => now()->addDays(25),
                'seating_enabled' => false,
                'total_capacity' => 5000,
                'created_by' => $admin->id,
                'ticket_types' => [
                    [
                        'name' => 'Regular Pass',
                        'description' => 'Access to all stages and general areas',
                        'price' => 1500000,
                        'quantity' => 3000,
                        'min_purchase' => 1,
                        'max_purchase' => 10,
                        'is_active' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'VIP Pass',
                        'description' => 'VIP access with premium seating and exclusive areas',
                        'price' => 3000000,
                        'quantity' => 500,
                        'min_purchase' => 1,
                        'max_purchase' => 5,
                        'is_active' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Backstage Pass',
                        'description' => 'Exclusive backstage access with meet & greet',
                        'price' => 5000000,
                        'quantity' => 50,
                        'min_purchase' => 1,
                        'max_purchase' => 2,
                        'is_active' => true,
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Tech Conference 2026',
                'description' => 'The premier technology conference featuring keynotes from industry leaders, hands-on workshops, and networking opportunities.',
                'event_date' => now()->addDays(45),
                'event_end_date' => now()->addDays(47),
                'location' => 'Grand Hyatt Jakarta',
                'venue_name' => 'Ballroom',
                'latitude' => -6.1944,
                'longitude' => 106.8229,
                'status' => 'published',
                'published_at' => now(),
                'sales_start_at' => now(),
                'sales_end_at' => now()->addDays(40),
                'seating_enabled' => true,
                'total_capacity' => 1000,
                'created_by' => $admin->id,
                'ticket_types' => [
                    [
                        'name' => 'Early Bird',
                        'description' => 'Limited early bird pricing',
                        'price' => 750000,
                        'quantity' => 200,
                        'min_purchase' => 1,
                        'max_purchase' => 5,
                        'is_active' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Regular',
                        'description' => 'Standard conference access',
                        'price' => 1000000,
                        'quantity' => 500,
                        'min_purchase' => 1,
                        'max_purchase' => 10,
                        'is_active' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Corporate',
                        'description' => 'Corporate package with additional benefits',
                        'price' => 2000000,
                        'quantity' => 100,
                        'min_purchase' => 1,
                        'max_purchase' => 20,
                        'is_active' => true,
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Food & Wine Festival',
                'description' => 'A culinary journey featuring top chefs, wine tastings, and gourmet food from around the world.',
                'event_date' => now()->addDays(60),
                'event_end_date' => now()->addDays(62),
                'location' => 'Senayan City',
                'venue_name' => 'Open Air Plaza',
                'latitude' => -6.2255,
                'longitude' => 106.7965,
                'status' => 'published',
                'published_at' => now(),
                'sales_start_at' => now(),
                'sales_end_at' => now()->addDays(55),
                'seating_enabled' => false,
                'total_capacity' => 2000,
                'created_by' => $admin->id,
                'ticket_types' => [
                    [
                        'name' => 'Day Pass',
                        'description' => 'Single day access',
                        'price' => 500000,
                        'quantity' => 1000,
                        'min_purchase' => 1,
                        'max_purchase' => 5,
                        'is_active' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Weekend Pass',
                        'description' => 'Access for entire weekend',
                        'price' => 800000,
                        'quantity' => 500,
                        'min_purchase' => 1,
                        'max_purchase' => 5,
                        'is_active' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'VIP Experience',
                        'description' => 'VIP access with exclusive tastings',
                        'price' => 1500000,
                        'quantity' => 100,
                        'min_purchase' => 1,
                        'max_purchase' => 3,
                        'is_active' => true,
                        'sort_order' => 3,
                    ],
                ],
            ],
        ];

        foreach ($events as $eventData) {
            $ticketTypes = $eventData['ticket_types'];
            unset($eventData['ticket_types']);

            $event = Event::firstOrCreate(
                ['title' => $eventData['title']],
                array_merge($eventData, [
                    'slug' => \Illuminate\Support\Str::slug($eventData['title']),
                ])
            );

            $this->command->info("✓ Event created: {$event->title}");

            foreach ($ticketTypes as $ticketTypeData) {
                TicketType::firstOrCreate(
                    [
                        'event_id' => $event->id,
                        'name' => $ticketTypeData['name'],
                    ],
                    array_merge($ticketTypeData, [
                        'sold_count' => 0,
                        'held_count' => 0,
                    ])
                );

                $this->command->info("  ✓ Ticket type created: {$ticketTypeData['name']}");
            }
        }

        $this->command->info('Event seeding completed successfully!');
    }
}
