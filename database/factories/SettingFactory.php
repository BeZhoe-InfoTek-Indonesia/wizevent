<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => 'setting-'.fake()->unique()->word(),
            'name' => fake()->sentence(3),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
