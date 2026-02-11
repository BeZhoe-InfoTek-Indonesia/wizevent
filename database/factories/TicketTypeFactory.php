<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketTypeFactory extends Factory
{
    protected $model = TicketType::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => fake()->word().' Ticket',
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10000, 500000),
            'quantity' => fake()->numberBetween(50, 1000),
            'sold_count' => fake()->numberBetween(0, 100),
            'min_purchase' => fake()->numberBetween(1, 5),
            'max_purchase' => fake()->numberBetween(5, 20),
            'sales_start_at' => fake()->dateTimeBetween('now', '+1 week'),
            'sales_end_at' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function active(): static
    {
        return static::state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return static::state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withSales(int $count = 10): static
    {
        return static::state(fn (array $attributes) => [
            'sold_count' => $count,
        ]);
    }
}
