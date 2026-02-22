<?php

namespace Database\Factories;

use App\Models\CmsPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoMetadataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'page_type' => fake()->randomElement(['CmsPage', 'Event']),
            'page_id' => CmsPage::factory(),
            'title' => fake()->optional()->sentence(5),
            'description' => fake()->optional()->text(160),
            'keywords' => fake()->optional()->words(5, true),
            'og_image' => fake()->optional()->imageUrl(),
            'canonical_url' => fake()->optional()->url(),
        ];
    }
}
