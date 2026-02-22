<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PromoCountdownFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'message' => fake()->sentence(8),
            'target_date' => fake()->dateTimeBetween('+1 hour', '+7 days'),
            'url' => fake()->optional()->url(),
            'is_active' => fake()->boolean(80),
            'display_location' => fake()->randomElement(['home', 'events', 'checkout', 'all']),
        ];
    }
}
