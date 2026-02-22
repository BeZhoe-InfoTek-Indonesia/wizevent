<?php

namespace App\Livewire\Event;

use App\Models\Event;
use App\Models\Favorite;
use App\Models\Testimonial;
use App\Models\TestimonialVote;
use App\Models\Ticket;
use App\Services\SocialShareService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-visitor')]
class EventDetail extends Component
{
    public Event $event;

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->with(['ticketTypes', 'categories', 'tags', 'banner', 'banners', 'creator', 'seoMetadata'])
            ->firstOrFail();
    }

    public function toggleFavorite(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('event_id', $this->event->id)
            ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'event_id' => $this->event->id,
            ]);
        }

        $this->event->refresh();
    }

    public function getIsFavoritedProperty(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->event->isLovedBy(Auth::user());
    }

    public function getShareUrlsProperty(): array
    {
        $service = app(SocialShareService::class);
        $urls = [];

        foreach ($service->getSupportedPlatforms() as $platform => $info) {
            $urls[$platform] = $service->getShareUrlForPlatform($this->event, $platform);
        }

        return $urls;
    }

    public function getEventUrlProperty(): string
    {
        return route('events.show', $this->event->slug);
    }

    public function getCalendarUrlProperty(): string
    {
        return route('events.calendar', $this->event->slug);
    }

    public function render(): View
    {
        $seo = $this->event->seoMetadata;

        return view('livewire.event.event-detail', [
            'event' => $this->event,
            'pageTitle' => $seo->title ?? $this->event->title,
            'metaDescription' => $seo->description ?? Str::limit(strip_tags($this->event->description), 160),
            'metaImage' => $seo->og_image ?? $this->event->banner?->url,
            'metaKeywords' => $seo->keywords ?? null,
            'canonicalUrl' => $seo->canonical_url ?? route('events.show', $this->event->slug),
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
            ->where('status', 'published')
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

    public function getApprovedTestimonialsProperty()
    {
        return Testimonial::where('event_id', $this->event->id)
            ->published()
            ->with(['user', 'votes'])
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->get();
    }

    public function getCanSubmitTestimonialProperty(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        $user = Auth::user();

        $testimonial = Testimonial::where('user_id', $user->id)
            ->where('event_id', $this->event->id)
            ->first();

        if ($testimonial) {
            return false;
        }

        $hasCheckedIn = Ticket::whereHas('ticketType', function ($query) {
            $query->where('event_id', $this->event->id);
        })->whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'completed');
        })->whereNotNull('checked_in_at')->exists();

        return $hasCheckedIn;
    }

    public function voteOnTestimonial(int $testimonialId, bool $isHelpful): void
    {
        if (! Auth::check()) {
            return;
        }

        $testimonial = Testimonial::find($testimonialId);
        if (! $testimonial || ! $testimonial->canBeVotedBy(Auth::user())) {
            return;
        }

        TestimonialVote::create([
            'testimonial_id' => $testimonialId,
            'user_id' => Auth::id(),
            'is_helpful' => $isHelpful,
        ]);
    }

    public function book()
    {
        if (! Auth::check()) {
            session(['url.intended' => route('events.show', $this->event->slug)]);

            return redirect()->route('login');
        }

        return redirect()->route('events.checkout', $this->event->slug);
    }
}
