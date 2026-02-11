<?php

namespace App\Livewire\Event;

use App\Models\Event;
use App\Models\SettingComponent;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.visitor')]
class EventList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?int $selectedCategory = null;

    #[Url]
    public string $dateRange = 'upcoming';

    #[Url]
    public ?string $startDate = null;

    #[Url]
    public ?string $endDate = null;

    #[Url]
    public ?float $minPrice = null;

    #[Url]
    public ?float $maxPrice = null;

    #[Url]
    public string $sort = 'date_asc';

    public int $perPage = 12;

    public function render(): View
    {
        $pageTitle = 'Discover Events';

        $query = Event::available()
            ->with(['categories', 'tags', 'banner']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('location', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->selectedCategory) {
            $query->whereHas('categories', function ($q) {
                $q->where('setting_components.id', $this->selectedCategory);
            });
        }

        if ($this->dateRange === 'custom' && $this->startDate) {
            $query->where('event_date', '>=', $this->startDate);
            if ($this->endDate) {
                $query->where('event_date', '<=', $this->endDate);
            }
        } elseif ($this->dateRange === 'upcoming') {
            $query->where('event_date', '>=', now());
        } elseif ($this->dateRange === 'this_week') {
            $query->whereBetween('event_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateRange === 'this_month') {
            $query->whereBetween('event_date', [now()->startOfMonth(), now()->endOfMonth()]);
        }

        if ($this->minPrice !== null || $this->maxPrice !== null) {
            $query->whereHas('ticketTypes', function ($q) {
                $q->where('is_active', true);
                if ($this->minPrice !== null) {
                    $q->where('price', '>=', $this->minPrice);
                }
                if ($this->maxPrice !== null) {
                    $q->where('price', '<=', $this->maxPrice);
                }
            });
        }

        switch ($this->sort) {
            case 'date_asc':
                $query->orderBy('event_date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('event_date', 'desc');
                break;
            case 'price_asc':
                $query->withMin('ticketTypes as min_price', 'price')->orderBy('min_price', 'asc');
                break;
            case 'price_desc':
                $query->withMin('ticketTypes as min_price', 'price')->orderBy('min_price', 'desc');
                break;
            default:
                $query->orderBy('event_date', 'asc');
        }

        $events = $query->paginate($this->perPage);
        $categories = SettingComponent::whereHas('setting', function ($q) {
            $q->where('key', 'event_categories');
        })->withCount(['events' => function($q) {
            $q->available();
        }])->get();

        $featuredEvents = Event::available()
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('livewire.event.event-list', [
            'events' => $events,
            'categories' => $categories,
            'featuredEvents' => $featuredEvents,
            'pageTitle' => $pageTitle,
        ]);
    }

    public function loadMore(): void
    {
        $this->perPage += 12;
    }

    public function viewEvent(int $id)
    {
        $event = Event::findOrFail($id);
        return redirect()->route('events.show', $event->slug);
    }

    public function filterByCategory(?int $id): void
    {
        $this->selectedCategory = $id;
        $this->resetPage();
    }

    public function viewAllCategories(): void
    {
        $this->selectedCategory = null;
        $this->resetPage();
    }

    public function filterByDate(string $filter): void
    {
        $this->dateRange = $filter;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategory = null;
        $this->dateRange = 'upcoming';
        $this->startDate = null;
        $this->endDate = null;
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->sort = 'date_asc';
        $this->perPage = 12;
        $this->resetPage();
    }
}
