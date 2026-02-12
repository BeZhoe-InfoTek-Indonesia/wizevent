<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\SettingComponent;
use App\Models\User;
use App\Services\SettingService;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    private SettingService $settingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settingService = new SettingService;
    }

    public function test_get_all_settings_returns_structured_data()
    {
        $user = User::factory()->create();

        $setting = Setting::create([
            'key' => 'app-name',
            'name' => 'Application Name',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        SettingComponent::create([
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'setting_id' => $setting->id,
            'name' => 'title',
            'type' => 'string',
            'value' => 'My App',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        SettingComponent::create([
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'setting_id' => $setting->id,
            'name' => 'max_users',
            'type' => 'integer',
            'value' => '100',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $settings = $this->settingService->getAllSettings();

        $this->assertArrayHasKey('app-name', $settings);
        $this->assertArrayHasKey('title', $settings['app-name']['components']);
        $this->assertArrayHasKey('max_users', $settings['app-name']['components']);
        $this->assertEquals('My App', $settings['app-name']['components']['title']['value']);
        $this->assertEquals(100, $settings['app-name']['components']['max_users']['value']);
        $this->assertEquals('string', $settings['app-name']['components']['title']['type']);
        $this->assertEquals('integer', $settings['app-name']['components']['max_users']['type']);
    }

    public function test_get_setting_by_key_returns_correct_data()
    {
        $user = User::factory()->create();

        $setting = Setting::create([
            'key' => 'test-setting',
            'name' => 'Test Setting',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $settingData = $this->settingService->getSettingByKey('test-setting');

        $this->assertNotNull($settingData);
        $this->assertEquals('test-setting', $settingData['key']);
        $this->assertEquals('Test Setting', $settingData['name']);
        $this->assertIsArray($settingData['components']);
    }

    public function test_get_setting_by_key_returns_null_for_nonexistent_key()
    {
        $settingData = $this->settingService->getSettingByKey('nonexistent-key');

        $this->assertNull($settingData);
    }

    public function test_get_component_value_returns_typed_value()
    {
        $user = User::factory()->create();

        $setting = Setting::create([
            'key' => 'test-setting',
            'name' => 'Test Setting',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        SettingComponent::create([
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'setting_id' => $setting->id,
            'name' => 'count',
            'type' => 'integer',
            'value' => '42',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $value = $this->settingService->getComponentValue('test-setting', 'count');

        $this->assertEquals(42, $value);
        $this->assertIsInt($value);
    }

    public function test_get_component_value_returns_null_for_nonexistent_component()
    {
        $value = $this->settingService->getComponentValue('nonexistent-key', 'nonexistent-component');

        $this->assertNull($value);
    }
}
