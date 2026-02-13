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

    // Bypass service worker for storage files to avoid returning 503
    if (event.request.method === 'GET' && url.pathname.startsWith('/storage/')) {
        // Prefer network, but fallback to cache if available
        event.respondWith(
            fetch(event.request).catch(async () => {
                const cached = await caches.match(event.request);
                if (cached) return cached;
                return new Response('Service Unavailable', { status: 503, statusText: 'Service Unavailable' });
            })
        );
        return;
    }

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
            fetch(event.request).catch(async () => {
                const response = await caches.match(event.request);
                if (response) return response;
                
                // Fallback for failed network requests that are not in cache
                return new Response("Network failure", {
                    status: 503,
                    statusText: "Service Unavailable",
                    headers: new Headers({ "Content-Type": "text/plain" }),
                });
            })
        );
    }
});
