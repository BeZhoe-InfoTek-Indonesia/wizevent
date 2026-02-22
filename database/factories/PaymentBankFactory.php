<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentBankFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bank_name' => fake()->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI', 'Maybank']),
            'account_number' => fake()->numerify('#############'),
            'account_holder' => fake()->name(),
            'qr_code_path' => fake()->optional()->imageUrl(300, 300),
            'logo_path' => fake()->optional()->imageUrl(100, 100),
            'is_active' => fake()->boolean(80),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
