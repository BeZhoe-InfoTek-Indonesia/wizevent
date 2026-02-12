<?php

namespace App\Services;

use App\Models\Event;

class CalendarService
{
    public function generateIcsContent(Event $event): string
    {
        $uid = 'event-'.$event->id.'@'.request()->getHost();
        $dtStart = $this->formatDateTime($event->event_date);
        $dtEnd = $this->formatDateTime($event->event_end_date ?? $event->event_date->addHours(2));
        $dtStamp = now()->utc()->format('Ymd\THis\Z');

        $content = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//'.config('app.name').'//Events//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            "UID:{$uid}",
            "DTSTAMP:{$dtStamp}",
            "DTSTART:{$dtStart}",
            "DTEND:{$dtEnd}",
            "SUMMARY:{$this->escapeText($event->title)}",
            "DESCRIPTION:{$this->escapeText($event->description)}",
            "LOCATION:{$this->escapeText($event->location)}",
            'URL:'.route('events.show', $event->slug),
            'STATUS:CONFIRMED',
            'TRANSP:OPAQUE',
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return implode("\r\n", $content);
    }

    protected function formatDateTime(\DateTimeInterface $dateTime): string
    {
        if ($dateTime instanceof \Carbon\Carbon) {
            return $dateTime->utc()->format('Ymd\THis\Z');
        }

        return (new \Carbon\Carbon($dateTime))->utc()->format('Ymd\THis\Z');
    }

    protected function escapeText(string $text): string
    {
        $text = strip_tags($text);
        $text = str_replace(["\r", "\n"], '\n', $text);
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace(';', '\;', $text);
        $text = str_replace(',', '\,', $text);

        return $text;
    }

    public function generateIcsFilename(Event $event): string
    {
        $safeName = preg_replace('/[^a-z0-9\-]/', '-', strtolower($event->title));
        $safeName = preg_replace('/-+/', '-', $safeName);
        $safeName = trim($safeName, '-');

        return $safeName.'-event.ics';
    }
}
