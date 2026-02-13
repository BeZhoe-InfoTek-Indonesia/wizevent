<?php

namespace App\Livewire\User;

use App\Models\Favorite;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-visitor')]
class LovedEventsList extends Component
{
    public function getLovedEventsProperty()
    {
        if (! Auth::check()) {
            return collect();
        }

        return Favorite::where('user_id', Auth::id())
            ->with(['event.ticketTypes', 'event.banner'])
            ->orderByDesc('created_at')
            ->get()
            ->pluck('event');
    }

    public function removeFavorite(int $eventId): void
    {
        if (! Auth::check()) {
            return;
        }

        Favorite::where('user_id', Auth::id())
            ->where('event_id', $eventId)
            ->delete();
    }

    public function render(): View
    {
        return view('livewire.user.loved-events-list');
    }
}
