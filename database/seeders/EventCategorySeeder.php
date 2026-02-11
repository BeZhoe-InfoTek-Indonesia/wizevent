<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Music', 'slug' => 'music', 'description' => 'Music events and concerts', 'icon' => '🎵', 'color' => '#E91E63', 'sort_order' => 1],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports events and competitions', 'icon' => '⚽', 'color' => '#22C55E', 'sort_order' => 2],
            ['name' => 'Arts', 'slug' => 'arts', 'description' => 'Arts and cultural events', 'icon' => '🎨', 'color' => '#9C27B0', 'sort_order' => 3],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business conferences and networking events', 'icon' => '💼', 'color' => '#2563EB', 'sort_order' => 4],
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Technology and startup events', 'icon' => '🔬', 'color' => '#3B82F6', 'sort_order' => 5],
            ['name' => 'Workshop', 'slug' => 'workshop', 'description' => 'Workshops and seminars', 'icon' => '📚', 'color' => '#795548', 'sort_order' => 6],
            ['name' => 'Party', 'slug' => 'party', 'description' => 'Parties and celebrations', 'icon' => '🎉', 'color' => '#FF6B6B', 'sort_order' => 7],
            ['name' => 'Charity', 'slug' => 'charity', 'description' => 'Charity and fundraising events', 'icon' => '💝', 'color' => '#D62746', 'sort_order' => 8],
        ];

        foreach ($categories as $category) {
            EventCategory::create($category);
        }
    }
}
