## 1. Setup & Assets
- [x] 1.1 Generate PWA icons (192x192, 512x512) and save to `public/icons/`
- [x] 1.2 Create `public/manifest.json` with app metadata
- [x] 1.3 Create `public/service-worker.js` with basic caching strategy

## 2. Integration
- [x] 2.1 Update `resources/views/layouts/app.blade.php` to link the manifest and register the service worker
- [x] 2.2 Add PWA-specific meta tags (theme-color, apple-mobile-web-app-capable, etc.)
- [x] 2.3 Implement an "Offline" notification banner using Alpine.js

## 3. Testing & Validation
- [x] 3.1 Verify manifest validation using browser dev tools (Lighthouse)
- [x] 3.2 Test "Add to Home Screen" functionality on a mobile device/emulator
- [x] 3.3 Test offline ticket access by simulating "Offline" mode in Chrome DevTools

## Post-Implementation
- [x] Update `AGENTS.md` and `project.md` to reflect new PWA capabilities
