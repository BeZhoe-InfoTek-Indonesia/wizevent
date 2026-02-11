# Event Discovery Architecture

## Overview
This feature implements the core public-facing pages for browsing, searching, and viewing events. We'll leverage Laravel Livewire for a reactive, SPA-like experience without the complexity of a full JS framework.

## Components & Modules

### 1. Event Listing (`Livewire\EventList`)
- **Responsibility**: Orchestrates search, filtering, and displaying the grid of events.
- **State**:
  - `$search` (string): User search query.
  - `$selectedCategory` (int|null): Filter by category ID.
  - `$dateRange` (string: 'upcoming'|'this_week'|'this_month'|'custom'): Filter by date.
  - `$startDate` / `$endDate` (Date|null): Custom date range.
  - `$minPrice` / `$maxPrice` (float|null): Price range filter.
  - `$sort` (string: 'date_asc'|'date_desc'|'price_asc'|'price_desc'): Sorting criteria.
- **Implementation Details**:
  - Uses `AvailableEventsScope` (or `published()` scope) to only show public events.
  - Debounced search input (`wire:model.live.debounce.300ms`).
  - URL query string synchronization (`#[Url]`) for shareable search results.

### 2. Event Filters (`Livewire\EventFilters`)
- **Responsibility**: A reusable component (or sidebar) for managing filter inputs.
- **Communication**: Emits events (`filterUpdated`) or directly binds to parent component properties if nested.
- **Data**: Loads categories and tags from `SettingComponent`.

### 3. Event Detail Page (`Livewire\EventDetail`)
- **Responsibility**: Shows the full details of a single event.
- **Route**: `/events/{slug}`.
- **Data**: Fetches `Event` with eager loaded `ticketTypes`, `venue`, and `media`.
- **UI Sections**:
  - Hero Header (Banner Image, Title, Date)
  - Details (Description, Map/Location)
  - Ticket Information (Types, Prices, Availability)
  - Sticky "Get Tickets" Button (Mobile)

## Database Considerations
- Ensure `events` table has indexes on:
  - `status` + `published_at` (for availability scope)
  - `event_date` (for date sorting/filtering)
  - `slug` (unique index for detail page lookup)
  - Full-text index on `title`, `description` (optional, for improved keyword search).
- `ticket_types` table indexes:
  - `price` (for price range filtering queries if needed).

## Performance Optimization
- Eager load relationships (`with('categories', 'banner')`) to prevent N+1 queries on the listing page.
- Use `simplePaginate()` for the event list to avoid expensive count queries if dataset grows large.
- Cache category list for filters (rarely changes).
- Optimize banner images using `FileBucket` thumbnails (e.g., `medium` size for grid, `large` for detail hero).

## SEO
- Dynamic meta tags (OpenGraph) on the detail page using event title, description, and banner image.
- Canonical URLs for event pages to avoid duplication issues.

## Testing Strategy
- **Feature Tests**: Verify search filters return correct subset of events.
- **Browser/Dusk Tests**: Ensure Livewire interactions (filtering, pagination) work correctly.
- **Performance**: Verify response time under load with seeded events.
