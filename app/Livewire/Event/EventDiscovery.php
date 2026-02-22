<?php

namespace App\Livewire\Event;

use App\Models\Banner;
use App\Models\Event;
use App\Models\SettingComponent;
use Livewire\Component;
use Livewire\WithPagination;

class EventDiscovery extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategories = [];
    public $selectedLocation = null;
    public $minPrice = 0;
    public $maxPrice = 10000000;
    public $sort = 'most_popular';
    public $dateFilter = 'all';
    public $startDate = null;
    public $endDate = null;
    
    public $showFilterModal = false;
    public $showLocationModal = false;
    public $locationSearch = '';
    public $activeTab = 'sort'; // sort, category, price, date

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategories' => ['except' => []],
        'selectedLocation' => ['except' => null],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 10000000],
        'sort' => ['except' => 'most_popular'],
        'dateFilter' => ['except' => 'all'],
        'startDate' => ['except' => null],
        'endDate' => ['except' => null],
    ];

    public function toggleFilterModal()
    {
        $this->showFilterModal = !$this->showFilterModal;
    }

    public function toggleLocationModal()
    {
        $this->showLocationModal = !$this->showLocationModal;
    }

    public function selectLocation($location)
    {
        $this->selectedLocation = $location;
        $this->showLocationModal = false;
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function applyFilters()
    {
        $this->showFilterModal = false;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedCategories = [];
        $this->selectedLocation = null;
        $this->minPrice = 0;
        $this->maxPrice = 10000000;
        $this->sort = 'most_popular';
        $this->dateFilter = 'all';
        $this->startDate = null;
        $this->endDate = null;
        $this->activeTab = 'sort';
        $this->resetPage();
    }

    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$categoryId]);
        } else {
            $this->selectedCategories[] = $categoryId;
        }
        $this->selectedCategories = array_values($this->selectedCategories);
    }

    public function toggleCategoryGroup($categoryIds, $checked)
    {
        if ($checked) {
            $this->selectedCategories = array_unique(array_merge($this->selectedCategories, $categoryIds));
        } else {
            $this->selectedCategories = array_diff($this->selectedCategories, $categoryIds);
        }
        $this->selectedCategories = array_values($this->selectedCategories);
    }

    public function trackBannerImpression(int $id): void
    {
        $banner = Banner::find($id);
        $banner?->incrementImpressionCount();
    }

    public function trackBannerClick(int $id): void
    {
        $banner = Banner::find($id);
        $banner?->incrementClickCount();
    }

    public function getLocationsProperty()
    {
        // Try to use Laravolt\Indonesia models
        try {
            if ($this->locationSearch) {
                $cities = \Laravolt\Indonesia\Models\City::where('name', 'like', '%' . $this->locationSearch . '%')
                    ->orderBy('name')
                    ->take(20)
                    ->pluck('name')
                    ->map(fn($name) => (string) str($name)->title())
                    ->toArray();
                    
                $provinces = \Laravolt\Indonesia\Models\Province::where('name', 'like', '%' . $this->locationSearch . '%')
                    ->orderBy('name')
                    ->take(10)
                    ->pluck('name')
                    ->map(fn($name) => (string) str($name)->title())
                    ->toArray();
                
                return [
                    'Results' => array_unique(array_merge($provinces, $cities))
                ];
            }

            $provinces = \Laravolt\Indonesia\Models\Province::orderBy('name')
                ->pluck('name')
                ->map(fn($name) => (string) str($name)->title())
                ->toArray();
            
            return [
                'Provinces' => $provinces,
            ];
        } catch (\Exception $e) {
            // Fallback if data not seeded or package issues
            return [
                'Fallback' => ['Jakarta', 'Bandung', 'Bali', 'Surabaya', 'Medan'],
            ];
        }
    }

    public function getGroupedCategoriesProperty()
    {
        $categories = SettingComponent::whereHas('setting', function ($q) {
            $q->where('key', 'event_categories');
        })->get();

        $groups = [];
        foreach ($categories as $cat) {
            $groupName = 'All Categories';
            
            if (stripos($cat->name, 'Pass') !== false || stripos($cat->name, 'Park') !== false || stripos($cat->name, 'Museum') !== false || stripos($cat->name, 'Site') !== false) {
                $groupName = 'Attractions';
            } elseif (stripos($cat->name, 'Play') !== false || stripos($cat->name, 'Indoor') !== false || stripos($cat->name, 'Arcade') !== false) {
                $groupName = 'Playground';
            }
            
            $groups[$groupName][] = $cat;
        }
        
        return $groups;
    }

    public function render()
    {
        $query = Event::published()->with(['banner', 'ticketTypes', 'categories', 'seoMetadata']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedLocation) {
            $query->where('location', 'like', '%' . $this->selectedLocation . '%');
        }

        if (!empty($this->selectedCategories)) {
            $query->whereHas('categories', function($q) {
                $q->whereIn('setting_components.id', $this->selectedCategories);
            });
        }

        if ($this->minPrice > 0 || $this->maxPrice < 10000000) {
            $query->whereHas('ticketTypes', function($q) {
                $q->where('price', '>=', $this->minPrice);
                if ($this->maxPrice < 10000000) {
                    $q->where('price', '<=', $this->maxPrice);
                }
            });
        }

        // Apply Date Filtering
        if ($this->dateFilter === 'today') {
            $query->whereDate('event_date', now()->toDateString());
        } elseif ($this->dateFilter === 'tomorrow') {
            $query->whereDate('event_date', now()->addDay()->toDateString());
        } elseif ($this->dateFilter === 'this_weekend') {
            $query->whereBetween('event_date', [
                now()->endOfWeek()->subDays(1)->startOfDay(), // Saturday
                now()->endOfWeek()->endOfDay() // Sunday
            ]);
        } elseif ($this->dateFilter === 'other' && ($this->startDate || $this->endDate)) {
            if ($this->startDate) {
                $query->whereDate('event_date', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('event_date', '<=', $this->endDate);
            }
        }

        switch ($this->sort) {
            case 'lowest_price':
                $query->withMin('ticketTypes as min_price', 'price')->orderBy('min_price', 'asc');
                break;
            case 'highest_price':
                $query->withMin('ticketTypes as min_price', 'price')->orderBy('min_price', 'desc');
                break;
            case 'newly_added':
                $query->latest();
                break;
            case 'most_popular':
                // For now, let's just use ID desc or something as a proxy for popular
                $query->orderByDesc('id');
                break;
            default:
                $query->orderBy('event_date', 'asc');
        }

        $events = $query->take(8)->get();

        $categories = SettingComponent::whereHas('setting', function ($q) {
            $q->where('key', 'event_categories');
        })->get();

        $heroBanners    = Banner::active()->scheduled()->byType('hero')->with('fileBucket')->orderBy('position')->get();
        $sectionBanners = Banner::active()->scheduled()->byType('section')->with('fileBucket')->orderBy('position')->get();
        $mobileBanner   = Banner::active()->scheduled()->byType('mobile')->with('fileBucket')->orderBy('position')->first();

        return view('livewire.event.event-discovery', [
            'events'         => $events,
            'categories'     => $categories,
            'heroBanners'    => $heroBanners,
            'sectionBanners' => $sectionBanners,
            'mobileBanner'   => $mobileBanner,
        ]);
    }
}
