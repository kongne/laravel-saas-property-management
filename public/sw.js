const CACHE_NAME = 'propertymanager-v1';
const STATIC_CACHE = 'propertymanager-static-v1';
const DYNAMIC_CACHE = 'propertymanager-dynamic-v1';
const API_CACHE = 'propertymanager-api-v1';

const PRECACHE_URLS = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-256x256.png',
    '/icons/icon-384x384.png',
    '/icons/icon-512x512.png',
];

const CDN_CACHE = [
    'cdn.jsdelivr.net',
    'fonts.googleapis.com',
    'fonts.gstatic.com',
    'code.jquery.com',
    'cdnjs.cloudflare.com',
];

const API_PATHS = ['/api/', '/dashboard'];

const MAX_DYNAMIC_ITEMS = 50;

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(STATIC_CACHE).then(function (cache) {
            return cache.addAll(PRECACHE_URLS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter(function (key) {
                        return key !== STATIC_CACHE && key !== DYNAMIC_CACHE && key !== API_CACHE;
                    })
                    .map(function (key) {
                        return caches.delete(key);
                    })
            );
        })
    );
    self.clients.claim();
});

function isApiRequest(url) {
    return url.pathname.startsWith('/api/') || url.pathname === '/dashboard';
}

function isStaticAsset(url) {
    return (
        url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot)$/) ||
        url.pathname === '/manifest.json'
    );
}

function isCDNRequest(url) {
    return CDN_CACHE.some(function (domain) {
        return url.hostname.includes(domain);
    });
}

function limitCacheSize(cacheName, maxItems) {
    caches.open(cacheName).then(function (cache) {
        cache.keys().then(function (keys) {
            if (keys.length > maxItems) {
                cache.delete(keys[0]).then(function () {
                    limitCacheSize(cacheName, maxItems);
                });
            }
        });
    });
}

self.addEventListener('fetch', function (event) {
    var request = event.request;
    var url = new URL(request.url);

    if (url.origin !== self.location.origin && !isCDNRequest(url)) {
        return;
    }

    if (request.method !== 'GET') {
        return;
    }

    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(request).then(function (cached) {
                return (
                    cached ||
                    fetch(request).then(function (response) {
                        return caches.open(STATIC_CACHE).then(function (cache) {
                            cache.put(request, response.clone());
                            return response;
                        });
                    })
                );
            })
        );
        return;
    }

    if (isApiRequest(url)) {
        event.respondWith(
            caches.match(request).then(function (cached) {
                var fetchPromise = fetch(request).then(function (response) {
                    if (response.ok) {
                        return caches.open(API_CACHE).then(function (cache) {
                            cache.put(request, response.clone());
                            limitCacheSize(API_CACHE, MAX_DYNAMIC_ITEMS);
                            return response;
                        });
                    }
                    return response;
                }).catch(function () {
                    return cached || caches.match('/offline.html');
                });

                return cached || fetchPromise;
            })
        );
        return;
    }

    event.respondWith(
        fetch(request)
            .then(function (response) {
                if (response.ok && response.type === 'basic') {
                    return caches.open(DYNAMIC_CACHE).then(function (cache) {
                        cache.put(request, response.clone());
                        limitCacheSize(DYNAMIC_CACHE, MAX_DYNAMIC_ITEMS);
                        return response;
                    });
                }
                return response;
            })
            .catch(function () {
                return caches.match(request).then(function (cached) {
                    return cached || caches.match('/offline.html');
                });
            })
    );
});

self.addEventListener('push', function (event) {
    var data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = { title: 'PropertyManager', body: event.data ? event.data.text() : 'New notification' };
    }

    var options = {
        body: data.body || 'You have a new notification.',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-192x192.png',
        vibrate: [200, 100, 200],
        data: {
            url: data.url || '/dashboard',
        },
        actions: [
            { action: 'open', title: 'Open App' },
            { action: 'dismiss', title: 'Dismiss' },
        ],
    };

    event.waitUntil(self.registration.showNotification(data.title || 'PropertyManager', options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.action === 'dismiss') return;

    var urlToOpen = event.notification.data?.url || '/dashboard';

    event.waitUntil(
        clients
            .matchAll({ type: 'window', includeUncontrolled: true })
            .then(function (windowClients) {
                for (var i = 0; i < windowClients.length; i++) {
                    var client = windowClients[i];
                    if (client.url.includes(self.location.origin) && 'focus' in client) {
                        return client.navigate(urlToOpen).then(function () { return client.focus(); });
                    }
                }
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});
