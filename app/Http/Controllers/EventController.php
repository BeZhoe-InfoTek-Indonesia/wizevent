<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\CalendarService;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function downloadCalendar(Event $event, CalendarService $calendarService): Response
    {
        $icsContent = $calendarService->generateIcsContent($event);
        $filename = $calendarService->generateIcsFilename($event);

        return response($icsContent, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Length' => strlen($icsContent),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
