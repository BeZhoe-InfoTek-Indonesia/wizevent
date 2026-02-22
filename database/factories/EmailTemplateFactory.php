<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailTemplateFactory extends Factory
{
    public function definition(): array
    {
        $key = fake()->slug(2);

        return [
            'key' => $key,
            'name' => fake()->words(3, true),
            'subject' => fake()->sentence(5),
            'html_content' => '<h1>{{ $title }}</h1><p>{{ $content }}</p>',
            'text_content' => fake()->optional()->paragraph(2),
            'variables' => ['title', 'content'],
            'locale' => fake()->randomElement(['en', 'id']),
            'is_default' => fake()->boolean(10),
        ];
    }
}
