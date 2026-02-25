<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\SettingComponent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SettingComponent>
 */
class SettingComponentFactory extends Factory
{
    protected $model = SettingComponent::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['string', 'integer', 'boolean']);

        $value = match ($type) {
            'string' => fake()->word(),
            'integer' => (string) fake()->randomNumber(),
            'boolean' => fake()->boolean() ? 'true' : 'false',
            default => fake()->word(),
        };

        return [
            'id' => (string) Str::ulid(),
            'setting_id' => Setting::factory(),
            'name' => fake()->word(),
            'type' => $type,
            'value' => $value,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
