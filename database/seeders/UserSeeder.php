<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * User Seeder
 *
 * Creates default users for the application:
 * - 1 Super Admin
 * - 3 Visitors
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role
        $admin->assignRole('Super Admin');

        $this->command->info('✓ Super Admin user created: admin@example.com / password');

        // Create 3 Visitors
        $visitors = [
            [
                'name' => 'John Visitor',
                'email' => 'visitor1@example.com',
            ],
            [
                'name' => 'Jane Visitor',
                'email' => 'visitor2@example.com',
            ],
            [
                'name' => 'Bob Visitor',
                'email' => 'visitor3@example.com',
            ],
        ];

        foreach ($visitors as $visitorData) {
            $visitor = User::firstOrCreate(
                ['email' => $visitorData['email']],
                [
                    'name' => $visitorData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Assign Visitor role
            $visitor->assignRole('Visitor');

            $this->command->info("✓ Visitor user created: {$visitorData['email']} / password");
        }

        $this->command->info('User seeding completed successfully!');
    }
}
