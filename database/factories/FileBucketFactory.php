<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileBucketFactory extends Factory
{
    protected $model = \App\Models\FileBucket::class;

    public function definition(): array
    {
        return [
            'fileable_type' => Event::class,
            'fileable_id' => Event::factory(),
            'bucket_type' => 'event-banners',
            'collection' => null,
            'original_filename' => fake()->word().'.jpg',
            'stored_filename' => fake()->uuid().'.jpg',
            'file_path' => 'banners/'.fake()->uuid().'.jpg',
            'url' => 'https://example.com/'.fake()->uuid().'.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(100000, 5000000),
            'width' => fake()->numberBetween(800, 1920),
            'height' => fake()->numberBetween(600, 1080),
            'metadata' => null,
            'sizes' => [
                'medium' => 'banners/'.fake()->uuid().'_medium.jpg',
                'large' => 'banners/'.fake()->uuid().'_large.jpg',
            ],
            'created_by' => User::factory(),
        ];
    }
}
