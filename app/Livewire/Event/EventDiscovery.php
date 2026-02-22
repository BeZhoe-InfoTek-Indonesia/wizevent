<?php

namespace App\Livewire\Event;

use App\Models\Banner;
use App\Models\Event;
use App\Models\SettingComponent;
use App\Models\Testimonial;
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

    /**
     * Get best seller events based on total tickets sold (OrderItem quantity)
     * Returns top 5 events with highest sales
     */
    protected function getBestSellerEvents()
    {
        return Event::with(['banner', 'ticketTypes', 'categories', 'seoMetadata', 'banners'])
            ->select('events.*')
            ->distinct()
            ->join('ticket_types', 'events.id', '=', 'ticket_types.event_id')
            ->join('order_items', 'ticket_types.id', '=', 'order_items.ticket_type_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('events.status', 'published')
            ->where('events.published_at', '<=', now())
            ->where('orders.status', 'completed')
            ->whereNull('events.deleted_at')
            ->groupBy('events.id')
            ->orderByRaw('SUM(order_items.quantity) DESC')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        $query = Event::published()->with(['banner', 'ticketTypes', 'categories', 'seoMetadata']);

        // ...existing code...

        $events = $query->take(8)->get();

        $categories = SettingComponent::whereHas('setting', function ($q) {
            $q->where('key', 'event_categories');
        })->get();

        $heroBanners    = Banner::active()->scheduled()->byType('hero')->with('fileBucket')->orderBy('position')->get();
        $sectionBanners = Banner::active()->scheduled()->byType('section')->with('fileBucket')->orderBy('position')->get();
        $mobileBanner   = Banner::active()->scheduled()->byType('mobile')->with('fileBucket')->orderBy('position')->first();

        $bestSellerEvents = $this->getBestSellerEvents();
        $testimonials = Testimonial::limit(10)->get();

        return view('livewire.event.event-discovery', [
            'events'           => $events,
            'categories'       => $categories,
            'heroBanners'      => $heroBanners,
            'sectionBanners'   => $sectionBanners,
            'mobileBanner'     => $mobileBanner,
            'bestSellerEvents' => $bestSellerEvents,
            'testimonials'     => $testimonials,
        ]);
    }
}
