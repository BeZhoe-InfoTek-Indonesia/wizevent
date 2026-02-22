<?php

namespace App\Livewire\Profile;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Wishlist extends Component
{
    use WithPagination;

    public $sortBy = 'recent';

    public function mount()
    {
        // Authorization check - users can view their own wishlist
        if (! auth()->check()) {
            abort(403, 'You must be logged in to view your wishlist.');
        }
    }

    public function setSortBy(string $sortBy)
    {
        $this->sortBy = $sortBy;
        $this->resetPage();
    }

    public function getWishlistProperty()
    {
        $query = Favorite::query()
            ->where('user_id', Auth::id())
            ->with(['event' => function ($query) {
                $query->with('ticketTypes');
            }]);

        if ($this->sortBy === 'recent') {
            $query->latest();
        } elseif ($this->sortBy === 'oldest') {
            $query->oldest();
        } elseif ($this->sortBy === 'event_date') {
            $query->orderByHas('event', function ($query) {
                $query->orderBy('event_date', 'asc');
            });
        }

        return $query->paginate(12);
    }

    public function removeFromWishlist(int $favoriteId)
    {
        $favorite = Favorite::where('id', $favoriteId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $favorite->delete();

        $this->dispatch('removed-from-wishlist', message: 'Event removed from wishlist.');
    }

    public function clearAll()
    {
        Favorite::where('user_id', Auth::id())->delete();
        $this->dispatch('removed-from-wishlist', message: 'Wishlist cleared successfully.');
    }

    public function render()
    {
        return view('livewire.profile.wishlist');
    }
}
