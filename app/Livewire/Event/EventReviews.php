<?php

namespace App\Livewire\Event;

use App\Models\Event;
use App\Models\Testimonial;
use App\Models\TestimonialVote;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app-visitor')]
class EventReviews extends Component
{
    public Event $event;

    public function mount(string $slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->firstOrFail();
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

        return Ticket::whereHas('ticketType', function ($query) {
            $query->where('event_id', $this->event->id);
        })->whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'completed');
        })->whereNotNull('checked_in_at')->exists();
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

    public function render(): View
    {
        return view('livewire.event.event-reviews', [
            'pageTitle' => 'Reviews - ' . $this->event->title,
        ]);
    }
}
