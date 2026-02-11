# Implement Event Discovery
The goal of this change is to implement the event discovery and search features as described in EPIC-004. This includes creating a public-facing event listing page with search and filtering capabilities, as well as detailed event view pages.

## Background
Currently, the system has a backend for managing events (EPIC-003), but visitors have no way to browse or search for these events. This change bridges that gap by providing the primary interface for users to discover events they might want to attend.

## Principles
- **Performance**: Search results must load quickly (<500ms).
- **Responsiveness**: The UI must be fully mobile-optimized as most traffic will come from mobile devices.
- **Simplicity**: Filters and search should be intuitive and easy to use.
- **Real-time Feedback**: Search results should update as the user types or adjusts filters (debounced).

## Models
- `Event`: The core model being searched.
- `TicketType`: Used to display price ranges.
- `SettingComponent`: Used for categories and tags filters.
- `FileBucket`: Used for serving optimized event banners.

## Scope
### In Scope
- Full-text keyword search (title, description, location).
- Filtering by date range, category, price, and status.
- Sorting by date, price, and relevance.
- Responsive grid layout for event cards.
- Detailed event page with rich description, location map, and ticket info.
- SEO-friendly URLs (slugs).

### Out of Scope
- Advanced full-text search engines (Elasticsearch/Meilisearch) - we will use database `LIKE` or full-text indices for now.
- Personalized recommendations.
- Ticket purchasing flow (handled in a separate EPIC).
- User favorites/saving events (future enhancement).

## UX/UI Design
- **List View**: A clean grid of event cards.
- **Filters**: On desktop, a sidebar. On mobile, a collapsible drawer/modal.
- **Event Card**: Thumbnail, Title, Date (formatted), Location, Lowest Price.
- **Detail View**: Large hero image, sticky booking CTA (mobile), detailed info, Google Maps integration.
