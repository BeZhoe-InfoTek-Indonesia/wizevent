<?php

namespace Database\Factories;

use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventCategoryFactory extends Factory
{
    protected $model = EventCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => fake()->slug(),
            'description' => fake()->sentence(),
            'icon' => 'heroicon-o-star',
            'color' => fake()->hexColor(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
