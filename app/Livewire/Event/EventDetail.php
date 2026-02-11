<?php

namespace App\Livewire\Event;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.visitor')]
class EventDetail extends Component
{
    public Event $event;

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->with(['ticketTypes', 'categories', 'tags', 'banner', 'banners', 'creator'])
            ->firstOrFail();
    }

    public function render(): View
    {
        return view('livewire.event.event-detail', [
            'event' => $this->event,
            'pageTitle' => $this->event->title,
            'metaDescription' => Str::limit(strip_tags($this->event->description), 160),
            'metaImage' => $this->event->banner?->url,
            'canonicalUrl' => route('events.show', $this->event->slug),
        ]);
    }

    public function getTicketTypesProperty()
    {
        return $this->event->ticketTypes->filter(function ($ticketType) {
            return $ticketType->is_active;
        })->sortBy('sort_order');
    }

    public function getRelatedEventsProperty()
    {
        return Event::where('id', '!=', $this->event->id)
            ->where('status', 'published') // Explicitly check status column
            ->where(function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->whereIn('setting_components.id', $this->event->categories->pluck('id'));
                })
                ->orWhereHas('tags', function ($q) {
                    $q->whereIn('setting_components.id', $this->event->tags->pluck('id'));
                });
            })
            ->limit(4)
            ->get();
    }
}
