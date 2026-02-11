1. **Setup Event Scope**:
    - [x] Create `app/Models/Event/AvaliableEventsScope.php` (or local scope `scopeAvailable` in `Event` model) to filter for `status='published'` and `published_at <= NOW()`.
    - [x] Ensure `ticket_types` logic is considered for "From Price".

2. **Create Event List Livewire Component**:
    - [x] Run `php artisan make:livewire EventList`.
    - [x] Implement `render()` method with search, date filter, status filter query.
    - [x] Add `#[Url]` attributes for query string binding.

3. **Implement Event List View**:
    - [x] Design responsive grid layout in `resources/views/livewire/event-list.blade.php`.
    - [x] Create reusable `checkout-event-card` Blade component for individual event items.
    - [x] Add loading state UI (skeleton loader).

4. **Create Event Detail Page**:
    - [x] Run `php artisan make:livewire EventDetail`.
    - [x] Create route `Route::get('/events/{slug}', EventDetail::class)->name('events.show');`.
    - [x] Implement `mount($slug)` to fetch event or 404.

5. **Implement Event Detail View**:
    - [x] Design layout in `resources/views/livewire/event-detail.blade.php`.
    - [x] Header with banner image (optimized via `FileBucket` URL helper).
    - [x] Sidebar with ticket info (types, prices, availability).
    - [x] Content area with description (rich text from database).
    - [x] Map component using `event.latitude` / `event.longitude`.

6. **SEO & Meta Tags**:
    - [x] Update `resources/views/layouts/app.blade.php` to accept dynamic meta tags.
    - [x] Set meta tags in `EventDetail` component `render()`/`mount()`.

7. **Integration & Polish**:
    - [x] Add "Events" link to main navigation menu.
    - [x] Verify mobile responsiveness on both list and detail pages.
    - [x] Check performance of search queries (EXPLAIN analyze if necessary).

8. **Testing**:
    - [x] Write Feature test `tests/Feature/EventDiscoveryTest.php`.
    - [x] Test search, date filtering, pagination, 404 behavior, sold out handling.

