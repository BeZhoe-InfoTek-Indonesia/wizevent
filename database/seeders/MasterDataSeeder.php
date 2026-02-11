<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingComponent;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Event Categories Setting
        $categoriesSetting = Setting::firstOrCreate(
            ['key' => 'event_categories'],
            ['name' => 'Event Categories']
        );

        $categories = [
            'Concert',
            'Conference',
            'Workshop',
            'Seminar',
            'Exhibition',
            'Festival',
            'Sports',
            'Other'
        ];

        foreach ($categories as $name) {
            SettingComponent::firstOrCreate([
                'setting_id' => $categoriesSetting->id,
                'name' => $name,
            ], [
                'type' => 'string',
                'value' => $name,
            ]);
        }

        // 2. Create Event Tags Setting
        $tagsSetting = Setting::firstOrCreate(
            ['key' => 'event_tags'],
            ['name' => 'Event Tags']
        );

        $tags = [
            'Tech',
            'Music',
            'Business',
            'Education',
            'Art',
            'Health',
            'Food',
            'Networking'
        ];

        foreach ($tags as $name) {
            SettingComponent::firstOrCreate([
                'setting_id' => $tagsSetting->id,
                'name' => $name,
            ], [
                'type' => 'string',
                'value' => $name,
            ]);
        }
    }
}
