<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.visitor')]
class Welcome extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        // Mock events data for now - will be replaced with actual Event model later
        $events = collect([
            (object) [
                'name' => 'Tech Conference 2026',
                'description' => 'Join us for the latest in technology innovation',
                'event_date' => now()->addDays(30),
            ],
            (object) [
                'name' => 'Music Festival',
                'description' => 'Experience amazing live performances',
                'event_date' => now()->addDays(45),
            ],
        ])->filter(function ($event) {
            return str_contains(strtolower($event->name), strtolower($this->search));
        });

        return view('livewire.welcome', [
            'events' => $events,
        ]);
    }
}
