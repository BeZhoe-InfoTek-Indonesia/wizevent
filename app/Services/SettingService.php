<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingService
{
    public function getAllSettings(): Collection
    {
        return Setting::with('components')
            ->orderBy('key')
            ->get()
            ->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'name' => $setting->name,
                    'components' => $setting->components->map(function ($component) {
                        return [
                            'name' => $component->name,
                            'type' => $component->type,
                            'value' => $this->getTypedValue($component),
                        ];
                    })->keyBy('name'),
                ];
            })
            ->keyBy('key');
    }

    public function getSettingByKey(string $key): ?array
    {
        $setting = Setting::with('components')
            ->where('key', $key)
            ->first();

        if (! $setting) {
            return null;
        }

        $components = $setting->components->map(function ($component) {
            return [
                'name' => $component->name,
                'type' => $component->type,
                'value' => $this->getTypedValue($component),
            ];
        })->keyBy('name');

        return [
            'key' => $setting->key,
            'name' => $setting->name,
            'components' => $components->toArray(),
        ];
    }

    public function getComponentValue(string $settingKey, string $componentName): mixed
    {
        $setting = Setting::where('key', $settingKey)->first();

        if (! $setting) {
            return null;
        }

        $component = $setting->components()->where('name', $componentName)->first();

        if (! $component) {
            return null;
        }

        return $this->getTypedValue($component);
    }

    private function getTypedValue($component): mixed
    {
        return match ($component->type) {
            'string' => (string) $component->value,
            'integer' => (int) $component->value,
            'boolean' => filter_var($component->value, FILTER_VALIDATE_BOOLEAN),
            'html' => (string) $component->value,
            default => $component->value,
        };
    }
}
