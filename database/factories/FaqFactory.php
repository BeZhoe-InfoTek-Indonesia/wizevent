<?php

namespace Database\Factories;

use App\Models\FaqCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => FaqCategory::factory(),
            'question' => fake()->sentence(5).'?',
            'answer' => fake()->paragraph(2),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => fake()->boolean(80),
        ];
    }
}
