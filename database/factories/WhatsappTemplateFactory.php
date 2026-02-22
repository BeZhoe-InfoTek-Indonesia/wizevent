<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WhatsappTemplateFactory extends Factory
{
    public function definition(): array
    {
        $key = fake()->slug(2);

        return [
            'key' => $key,
            'name' => fake()->words(3, true),
            'content' => 'Hello {{ $name }}, your order {{ $order_id }} is confirmed.',
            'variables' => ['name', 'order_id'],
            'locale' => fake()->randomElement(['en', 'id']),
            'is_default' => fake()->boolean(10),
        ];
    }
}
