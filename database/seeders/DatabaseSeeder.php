<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            SettingComponentSeeder::class,
            EventSeeder::class,
            OrderSeeder::class,
            TestimonialSeeder::class,
            OrganizerSeeder::class,
            PerformerSeeder::class,
            CmsSeeder::class,
        ]);
    }
}
