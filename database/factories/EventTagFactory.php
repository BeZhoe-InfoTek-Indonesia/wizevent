<?php

namespace Database\Factories;

use App\Models\EventTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventTagFactory extends Factory
{
    protected $model = EventTag::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => fake()->slug(),
        ];
    }
}
