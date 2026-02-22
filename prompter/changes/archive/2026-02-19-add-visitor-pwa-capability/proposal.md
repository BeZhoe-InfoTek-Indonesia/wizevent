# Change: Add Visitor PWA Capability

## Why
The visitor interface needs to be accessible, fast, and reliable, especially for users at event venues where internet connectivity might be spotty. Implementing Progressive Web App (PWA) features like service workers and manifest files will allow users to "install" the app, access their tickets offline, and experience faster load times through asset caching.

## What Changes
- Add a PWA Web Manifest to allow the app to be installed on home screens.
- Implement a Service Worker for offline asset caching and ticket availability.
- Configure Laravel to serve the service worker and manifest.
- Update the layout to include meta tags for mobile optimization and PWA detection.
- Add an "Offline Mode" indicator to the UI.

## Impact
- **Affected specs**: New `pwa-capability` spec; potential updates to `visitor-dashboard`.
- **Affected code**: 
    - `resources/views/layouts/app.blade.php` (meta tags)
    - `public/manifest.json` (new)
    - `public/service-worker.js` (new)
    - `app/Providers/AppServiceProvider.php` (potentially for PWA-related logic)
