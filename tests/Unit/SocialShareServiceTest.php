<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Services\SocialShareService;
use Tests\TestCase;

class SocialShareServiceTest extends TestCase
{
    protected SocialShareService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SocialShareService::class);
    }

    public function test_generate_share_url_includes_utm_parameters()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $shareUrl = $this->service->generateShareUrl($event, 'twitter');

        $this->assertStringContainsString('utm_source=twitter', $shareUrl);
        $this->assertStringContainsString('utm_medium=social', $shareUrl);
        $this->assertStringContainsString('utm_campaign=event_share', $shareUrl);
        $this->assertStringContainsString($event->slug, $shareUrl);
    }

    public function test_get_share_url_for_platform_facebook()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $shareUrl = $this->service->getShareUrlForPlatform($event, 'facebook');

        $this->assertStringStartsWith('https://www.facebook.com/sharer/sharer.php', $shareUrl);
        $this->assertStringContainsString('u=', $shareUrl);
        $this->assertStringContainsString('quote=', $shareUrl);
    }

    public function test_get_share_url_for_platform_twitter()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $shareUrl = $this->service->getShareUrlForPlatform($event, 'twitter');

        $this->assertStringStartsWith('https://twitter.com/intent/tweet', $shareUrl);
        $this->assertStringContainsString('url=', $shareUrl);
        $this->assertStringContainsString('text=', $shareUrl);
    }

    public function test_get_share_url_for_platform_whatsapp()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $shareUrl = $this->service->getShareUrlForPlatform($event, 'whatsapp');

        $this->assertStringStartsWith('https://wa.me/', $shareUrl);
        $this->assertStringContainsString('text=', $shareUrl);
    }

    public function test_get_share_url_for_platform_email()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $shareUrl = $this->service->getShareUrlForPlatform($event, 'email');

        $this->assertStringStartsWith('mailto:', $shareUrl);
        $this->assertStringContainsString('subject=', $shareUrl);
        $this->assertStringContainsString('body=', $shareUrl);
    }

    public function test_get_supported_platforms_returns_all_platforms()
    {
        $platforms = $this->service->getSupportedPlatforms();

        $this->assertIsArray($platforms);
        $this->assertArrayHasKey('facebook', $platforms);
        $this->assertArrayHasKey('twitter', $platforms);
        $this->assertArrayHasKey('linkedin', $platforms);
        $this->assertArrayHasKey('whatsapp', $platforms);
        $this->assertArrayHasKey('telegram', $platforms);
        $this->assertArrayHasKey('email', $platforms);
    }

    public function test_each_supported_platform_has_required_keys()
    {
        $platforms = $this->service->getSupportedPlatforms();

        foreach ($platforms as $platform) {
            $this->assertArrayHasKey('name', $platform);
            $this->assertArrayHasKey('icon', $platform);
            $this->assertArrayHasKey('color', $platform);
        }
    }
}
