<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentInstructionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_method' => fake()->randomElement(['transfer', 'bank', 'ewallet', 'qris']),
            'content' => fake()->paragraph(3),
            'is_active' => fake()->boolean(80),
            'locale' => fake()->randomElement(['en', 'id']),
        ];
    }
}
