<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CmsPageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => $title,
            'slug' => str()->slug($title),
            'content' => [
                [
                    'type' => 'text',
                    'content' => fake()->paragraph(3),
                ],
            ],
            'status' => fake()->randomElement(['draft', 'published']),
            'seo_title' => fake()->optional()->sentence(5),
            'seo_description' => fake()->optional()->text(160),
            'og_image' => fake()->optional()->imageUrl(),
            'published_at' => fake()->optional(0.5)->dateTimeBetween('-1 year', '+1 month'),
        ];
    }
}
