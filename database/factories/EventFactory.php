<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'slug' => fake()->slug(),
            'description' => fake()->paragraphs(3, true),
            'event_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'event_end_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'location' => fake()->address(),
            'venue_name' => fake()->company(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'google_place_id' => fake()->randomNumber(8),
            'status' => 'draft',
            'published_at' => null,
            'sales_start_at' => fake()->dateTimeBetween('now', '+1 week'),
            'sales_end_at' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'seating_enabled' => fake()->boolean(),
            'total_capacity' => fake()->numberBetween(100, 5000),
            'cancellation_reason' => null,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }

    public function published(): static
    {
        return static::state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return static::state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function cancelled(): static
    {
        return static::state(fn (array $attributes) => [
            'status' => 'cancelled',
            'cancellation_reason' => fake()->sentence(),
        ]);
    }

    public function soldOut(): static
    {
        return static::state(fn (array $attributes) => [
            'status' => 'sold_out',
        ]);
    }
}
