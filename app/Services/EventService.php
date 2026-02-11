<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Str;

class EventService
{
    public function createEvent(array $data): Event
    {
        $data['slug'] = Str::slug($data['title']);

        $event = Event::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'event_date' => $data['event_date'],
            'event_end_date' => $data['event_end_date'] ?? null,
            'location' => $data['location'],
            'venue_name' => $data['venue_name'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'google_place_id' => $data['google_place_id'] ?? null,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->log('Event created');

        return $event;
    }

    public function updateEvent(Event $event, array $data): Event
    {
        if (isset($data['title']) && $data['title'] !== $event->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        $event->update([
            'title' => $data['title'] ?? $event->title,
            'slug' => $data['slug'] ?? $event->slug,
            'description' => $data['description'] ?? $event->description,
            'event_date' => $data['event_date'] ?? $event->event_date,
            'event_end_date' => $data['event_end_date'] ?? $event->event_end_date,
            'location' => $data['location'] ?? $event->location,
            'venue_name' => $data['venue_name'] ?? $event->venue_name,
            'latitude' => $data['latitude'] ?? $event->latitude,
            'longitude' => $data['longitude'] ?? $event->longitude,
            'google_place_id' => $data['google_place_id'] ?? $event->google_place_id,
            'category_id' => $data['category_id'] ?? $event->category_id,
            'updated_by' => auth()->id(),
        ]);

        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->log('Event updated');

        return $event->refresh();
    }

    public function publishEvent(Event $event): Event
    {
        $errors = $this->validateForPublishing($event);

        if (! empty($errors)) {
            throw new \Illuminate\Validation\ValidationException($errors);
        }

        $event->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $event->refresh();
        $event->total_capacity = $this->calculateTotalCapacity($event);

        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->log('Event published');

        return $event;
    }

    public function cancelEvent(Event $event, string $reason): Event
    {
        if (! $event->canBeCancelled()) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Event cannot be cancelled');
        }

        $event->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);

        activity()
            ->performedOn($event)
            ->causedBy(auth()->user())
            ->withProperties(['reason' => $reason])
            ->log('Event cancelled');

        return $event->refresh();
    }

    public function validateForPublishing(Event $event): array
    {
        $errors = [];

        if (empty($event->title) || strlen($event->title) < 5 || strlen($event->title) > 200) {
            $errors[] = 'Title must be between 5 and 200 characters';
        }

        if (empty($event->description) || strlen($event->description) < 50 || strlen($event->description) > 10000) {
            $errors[] = 'Description must be between 50 and 10,000 characters';
        }

        if (empty($event->event_date)) {
            $errors[] = 'Event date is required';
        }

        if (empty($event->location)) {
            $errors[] = 'Location is required';
        }

        if (empty($event->banner_image)) {
            $errors[] = 'Banner image is required';
        }

        if ($event->ticketTypes()->where('is_active', true)->count() === 0) {
            $errors[] = 'At least one active ticket type is required';
        }

        return $errors;
    }

    public function calculateTotalCapacity(Event $event): int
    {
        return $event->ticketTypes()->sum('quantity');
    }
}
