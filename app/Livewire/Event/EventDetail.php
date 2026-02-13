<?php

namespace App\Livewire\Event;

use App\Models\Event;
use App\Models\Favorite;
use App\Models\Testimonial;
use App\Models\TestimonialVote;
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

    public string $testimonialContent = '';

    public int $testimonialRating = 5;

    public bool $showTestimonialForm = false;

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->with(['ticketTypes', 'categories', 'tags', 'banner', 'banners', 'creator'])
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
            ->approved()
            ->with(['user', 'votes'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getCanSubmitTestimonialProperty(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        $user = Auth::user();

        return Testimonial::where('event_id', $this->event->id)
            ->where('user_id', $user->id)
            ->doesntExist()
            && $this->event->hasPurchasedTicket($user);
    }

    public function submitTestimonial(): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->validate([
            'testimonialContent' => 'required|min:10|max:1000',
            'testimonialRating' => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create([
            'user_id' => Auth::id(),
            'event_id' => $this->event->id,
            'content' => $this->testimonialContent,
            'rating' => $this->testimonialRating,
            'status' => 'pending',
        ]);

        $this->testimonialContent = '';
        $this->testimonialRating = 5;
        $this->showTestimonialForm = false;

        session()->flash('testimonial_submitted', 'Thank you! Your testimonial has been submitted and will be published after moderation.');
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
}
