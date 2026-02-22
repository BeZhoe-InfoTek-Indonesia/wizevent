<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'type' => fake()->randomElement(['hero', 'section', 'mobile']),
            'image_path' => fake()->imageUrl(1200, 400),
            'link_url' => fake()->optional()->url(),
            'link_target' => fake()->randomElement(['_self', '_blank']),
            'position' => fake()->numberBetween(0, 10),
            'is_active' => fake()->boolean(80),
            'start_date' => fake()->optional(0.7)->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => fake()->optional(0.5)->dateTimeBetween('+1 week', '+3 months'),
            'click_count' => fake()->numberBetween(0, 1000),
            'impression_count' => fake()->numberBetween(100, 10000),
        ];
    }
}
