<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . now()->timestamp . '-' . strtoupper($this->faker->lexify('??????')),
            'user_id' => \App\Models\User::factory(),
            'event_id' => \App\Models\Event::factory(),
            'status' => 'pending_payment',
            'subtotal' => 100,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 100,
            'expires_at' => now()->addHours(24),
        ];
    }
}
