# Design: PWA Implementation Strategy

## Context
The Event Management system serves visitors who often need access to their digital tickets in environments with unstable internet (venues, stadium, etc.). PWA features will bridge the gap between a web app and a native experience.

## Goals / Non-Goals
- **Goals**:
    - "Installable" web app status (A2HS).
    - Offline access to previously viewed tickets.
    - Caching of static assets (JS, CSS, Images).
    - Sub-second loading for repeat visitors.
- **Non-Goals**:
    - Push notifications (deferred to a future epic).
    - Full offline CRUD (users should not expect to *buy* tickets offline).

## Decisions

### 1. Service Worker Strategy
- **Strategy**: Cache First for static assets, Network First for dynamic content (API/Livewire).
- **Fallback**: Provide an "Offline" page if a route is not cached and the network is down.
- **Tooling**: Use vanilla Service Worker API for maximum control and zero bloat, rather than Workbox, to keep implementations minimal as per project guidelines.

### 2. Manifest Configuration
- **Display**: `standalone` to look like a native app.
- **Theme Color**: Aligned with the project's primary brand color (WireUI/Tailwind theme).
- **Icons**: At least 192x192 and 512x512 icons generated.

### 3. Offline Ticket Storage
- Service worker will intercept requests to `/tickets/*` and cache the generated HTML/assets.
- Alternatively, use `LocalStorage` or `IndexedDB` to store QR code data if needed for more complex offline verification (though HTML caching is simpler for a V1).

## Risks / Trade-offs
- **Stale Content**: Using "Cache First" for assets requires robust versioning (Laravel Vite handles this via hashes).
- **Browser Compatibility**: Safari on iOS has limited PWA support compared to Chrome/Android, but basic manifest/A2HS works.

## Migration Plan
- No database migrations required.
- Deployment involves serving `manifest.json` and `service-worker.js` from the public root.
