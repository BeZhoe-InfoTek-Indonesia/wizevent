<?php

use App\Models\Event;
use App\Models\TicketType;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing Event -> TicketType loading...\n";
    $event = Event::first();
    if ($event) {
        $event->load('ticketTypes'); // Should already be loaded by $with
        echo "Event ID: " . $event->id . "\n";
        echo "Ticket Types Count: " . $event->ticketTypes->count() . "\n";
        
        $ticketType = $event->ticketTypes->first();
        if ($ticketType) {
             echo "Testing TicketType -> Event loading...\n";
             // Accessing event relation should work but not be eager loaded automatically to cause loop
             echo "Ticket Event ID: " . $ticketType->event->id . "\n";
        }
    } else {
        echo "No events found to test.\n";
    }
    
    echo "Success! No circular dependency crash.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
