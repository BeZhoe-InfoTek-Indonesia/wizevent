const CACHE_NAME = 'event-ticket-cache-v1';

// We cache specific ticket views as they are visited
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Only cache GET requests for tickets
    if (event.request.method === 'GET' && url.pathname.startsWith('/tickets/')) {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    // Clone the response to save it in cache
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });
                    return response;
                })
                .catch(() => {
                    // If network fails, try the cache
                    return caches.match(event.request);
                })
        );
    } else {
        // Default strategy: Network first
        event.respondWith(
            fetch(event.request).catch(() => caches.match(event.request))
        );
    }
});
