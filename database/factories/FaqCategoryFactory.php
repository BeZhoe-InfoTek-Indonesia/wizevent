<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FaqCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(2, true);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'order' => fake()->numberBetween(0, 10),
            'is_active' => fake()->boolean(80),
        ];
    }
}
