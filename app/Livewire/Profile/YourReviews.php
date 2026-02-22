<?php

namespace App\Livewire\Profile;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class YourReviews extends Component
{
    use WithPagination;

    public $statusFilter = 'all';

    public function mount()
    {
        // Authorization check - users can view their own reviews
        if (! auth()->check()) {
            abort(403, 'You must be logged in to view your reviews.');
        }
    }

    public function setStatusFilter(string $status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public $sort = 'latest';

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function getReviewsProperty()
    {
        $query = Testimonial::query()
            ->where('user_id', Auth::id())
            ->with(['event', 'user'])
            ->withCount(['votes as helpful_votes_count' => function ($query) {
                $query->where('is_helpful', true);
            }])
            ->withCount(['votes as not_helpful_votes_count' => function ($query) {
                $query->where('is_helpful', false);
            }]);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        match ($this->sort) {
            'oldest' => $query->oldest(),
            'rating_high' => $query->orderByDesc('rating'),
            'rating_low' => $query->orderBy('rating'),
            default => $query->latest(),
        };

        return $query->paginate(10);
    }

    public function getTotalHelpfulVotesProperty()
    {
        return Testimonial::where('testimonials.user_id', Auth::id())
            ->join('testimonial_votes', 'testimonials.id', '=', 'testimonial_votes.testimonial_id')
            ->where('testimonial_votes.is_helpful', true)
            ->count();
    }

    public function getPendingReviewsCountProperty()
    {
        return Testimonial::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->count();
    }

    public function getEventsToReviewProperty()
    {
        $reviewedEventIds = Testimonial::where('user_id', Auth::id())->pluck('event_id');

        return \App\Models\Event::query()
            ->whereHas('orders', function ($query) {
                $query->where('user_id', Auth::id())
                      ->where('status', 'completed');
            })
            ->whereNotIn('id', $reviewedEventIds)
            ->where('event_end_date', '<', now())
            ->latest('event_end_date')
            ->paginate(10);
    }

    public function getEventsToReviewCountProperty()
    {
        $reviewedEventIds = Testimonial::where('user_id', Auth::id())->pluck('event_id');

        return \App\Models\Event::query()
            ->whereHas('orders', function ($query) {
                $query->where('user_id', Auth::id())
                      ->where('status', 'completed');
            })
            ->whereNotIn('id', $reviewedEventIds)
            ->where('event_end_date', '<', now())
            ->count();
    }

    public function getTotalReviewsCountProperty()
    {
        return Testimonial::where('user_id', Auth::id())->count();
    }

    public function deleteReview(int $reviewId)
    {
        $review = Testimonial::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->delete();

        $this->dispatch('review-deleted', message: 'Review deleted successfully.');
    }

    public function render()
    {
        return view('livewire.profile.your-reviews');
    }
}
