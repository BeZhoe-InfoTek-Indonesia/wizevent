<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\SettingComponent;
use App\Models\User;
use Tests\TestCase;

class SettingTest extends TestCase
{
    public function test_setting_can_be_created()
    {
        $setting = Setting::create([
            'key' => 'test-setting',
            'name' => 'Test Setting',
            'created_by' => User::factory()->create()->id,
            'updated_by' => User::factory()->create()->id,
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'test-setting',
            'name' => 'Test Setting',
        ]);
    }

    public function test_setting_key_is_unique()
    {
        $user = User::factory()->create();

        Setting::create([
            'key' => 'test-setting',
            'name' => 'Test Setting',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Setting::create([
            'key' => 'test-setting',
            'name' => 'Test Setting 2',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    }

    public function test_setting_has_many_components()
    {
        $setting = Setting::factory()->create();

        $component1 = SettingComponent::factory()->create(['setting_id' => $setting->id]);
        $component2 = SettingComponent::factory()->create(['setting_id' => $setting->id]);

        $this->assertCount(2, $setting->components);
        $this->assertContains($component1->id, $setting->components->pluck('id')->toArray());
        $this->assertContains($component2->id, $setting->components->pluck('id')->toArray());
    }

    public function test_setting_soft_deletes()
    {
        $setting = Setting::factory()->create();
        $setting->delete();

        $this->assertSoftDeleted($setting);
        $this->assertDatabaseHas('settings', ['id' => $setting->id, 'deleted_at' => $setting->deleted_at]);
    }
}
