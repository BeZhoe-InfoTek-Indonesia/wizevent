const CACHE_NAME = 'event-mgmt-v1';
const CACHE_DURATION = 7 * 24 * 60 * 60 * 1000;

const ASSETS_CACHE = 'event-mgmt-assets-v1';
const DYNAMIC_CACHE = 'event-mgmt-dynamic-v1';

const STATIC_ASSETS = [
    '/',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(ASSETS_CACHE).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => {
                        return cacheName.startsWith('event-mgmt-') && cacheName !== ASSETS_CACHE && cacheName !== DYNAMIC_CACHE;
                    })
                    .map((cacheName) => {
                        return caches.delete(cacheName);
                    })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    if (url.origin !== location.origin) {
        return;
    }

    if (isStaticAsset(url.pathname)) {
        event.respondWith(handleStaticAsset(event.request));
    } else if (isDynamicContent(url.pathname)) {
        event.respondWith(handleDynamicContent(event.request));
    } else {
        event.respondWith(handleNavigation(event.request));
    }
});

function isStaticAsset(pathname) {
    return /\.(css|js|svg|png|jpg|jpeg|gif|webp|woff|woff2|ttf|eot|ico)$/i.test(pathname);
}

function isDynamicContent(pathname) {
    return pathname.startsWith('/livewire/') || 
           pathname.startsWith('/api/') ||
           pathname.startsWith('/storage/');
}

async function handleStaticAsset(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            const cachedDate = new Date(cachedResponse.headers.get('date'));
            if (Date.now() - cachedDate.getTime() < CACHE_DURATION) {
                return cachedResponse;
            }
        }

        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(ASSETS_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        throw error;
    }
}

async function handleDynamicContent(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        throw error;
    }
}

async function handleNavigation(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse && networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
            return networkResponse;
        }
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        const offlineCache = await caches.open(ASSETS_CACHE);
        const offlineResponse = await offlineCache.match('/');
        if (offlineResponse) {
            return offlineResponse;
        }
        
        return new Response('Offline. Please check your internet connection.', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
                'Content-Type': 'text/plain'
            })
        });
    }
}

self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
