<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Services\CalendarService;
use Tests\TestCase;

class CalendarServiceTest extends TestCase
{
    protected CalendarService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CalendarService::class);
    }

    public function test_generate_ics_content_returns_valid_format()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'event_date' => now()->addDays(7),
            'event_end_date' => now()->addDays(7)->addHours(3),
            'location' => 'Test Location',
            'description' => 'Test Description',
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringContainsString('BEGIN:VCALENDAR', $icsContent);
        $this->assertStringContainsString('VERSION:2.0', $icsContent);
        $this->assertStringContainsString('BEGIN:VEVENT', $icsContent);
        $this->assertStringContainsString('END:VEVENT', $icsContent);
        $this->assertStringContainsString('END:VCALENDAR', $icsContent);
    }

    public function test_generate_ics_content_includes_event_details()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event Title',
            'slug' => 'test-event',
            'event_date' => now()->addDays(7)->setHour(14)->setMinute(30),
            'event_end_date' => now()->addDays(7)->addHours(3)->setHour(17)->setMinute(30),
            'location' => '123 Test Street, City',
            'description' => 'This is a test event description.',
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringContainsString('SUMMARY:Test Event Title', $icsContent);
        $this->assertStringContainsString('LOCATION:123 Test Street, City', $icsContent);
        $this->assertStringContainsString('DESCRIPTION:This is a test event description.', $icsContent);
    }

    public function test_generate_ics_content_formats_dates_correctly()
    {
        $eventDate = now()->addDays(7)->setHour(14)->setMinute(30);
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'event_date' => $eventDate,
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $expectedDate = $eventDate->utc()->format('Ymd\THis\Z');
        $this->assertStringContainsString("DTSTART:{$expectedDate}", $icsContent);
    }

    public function test_generate_ics_content_includes_unique_identifier()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringContainsString('UID:event-'.$event->id.'@', $icsContent);
    }

    public function test_generate_ics_content_includes_url()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringContainsString('URL:'.route('events.show', $event->slug), $icsContent);
    }

    public function test_generate_ics_filename_returns_valid_name()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event Name 2024!',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $filename = $this->service->generateIcsFilename($event);

        $this->assertStringEndsWith('.ics', $filename);
        $this->assertStringContainsString('test-event-name-2024', $filename);
        $this->assertStringNotContainsString('!', $filename);
    }

    public function test_generate_ics_content_escapes_special_characters()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event; with, special\\chars',
            'slug' => 'test-event',
            'location' => 'Location; with, commas',
            'description' => 'Description with; special\\ characters',
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringContainsString('\;', $icsContent);
        $this->assertStringContainsString('\,', $icsContent);
        $this->assertStringContainsString('\\\\', $icsContent);
    }

    public function test_generate_ics_content_handles_multiline_descriptions()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'description' => "Line 1\nLine 2\nLine 3",
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringContainsString('Line 1\\nLine 2\\nLine 3', $icsContent);
        $this->assertStringNotContainsString("\n", $icsContent);
    }

    public function test_generate_ics_content_strips_html_tags()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'description' => '<p>HTML <b>description</b> with <a href="#">link</a></p>',
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringNotContainsString('<p>', $icsContent);
        $this->assertStringNotContainsString('<b>', $icsContent);
        $this->assertStringNotContainsString('<a', $icsContent);
        $this->assertStringContainsString('HTML description with link', $icsContent);
    }

    public function test_generate_ics_content_includes_status_confirmed()
    {
        $event = Event::factory()->create([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'published',
        ]);

        $icsContent = $this->service->generateIcsContent($event);

        $this->assertStringContainsString('STATUS:CONFIRMED', $icsContent);
    }
}
