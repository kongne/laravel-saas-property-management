const CACHE_NAME = 'propman-v3';
const STATIC_CACHE = 'propman-static-v3';
const DYNAMIC_CACHE = 'propman-dynamic-v3';

const PRECACHE_URLS = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-256x256.png',
    '/icons/icon-384x384.png',
    '/icons/icon-512x512.png',
];

const MAX_DYNAMIC_ITEMS = 100;

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(STATIC_CACHE).then(function (cache) {
            return cache.addAll(PRECACHE_URLS);
        }).catch(function () {})
    );
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter(function (key) {
                        return key !== STATIC_CACHE && key !== DYNAMIC_CACHE;
                    })
                    .map(function (key) {
                        return caches.delete(key);
                    })
            );
        })
    );
    self.clients.claim();
});

function isStaticAsset(url) {
    return url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot)$/) ||
        url.pathname === '/manifest.json' ||
        url.pathname.startsWith('/build/');
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

    if (url.origin !== self.location.origin) return;
    if (request.method !== 'GET') return;

    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(request).then(function (cached) {
                return cached || fetch(request).then(function (response) {
                    return caches.open(STATIC_CACHE).then(function (cache) {
                        cache.put(request, response.clone());
                        return response;
                    });
                }).catch(function () {
                    return caches.match('/offline.html');
                });
            })
        );
        return;
    }

    event.respondWith(
        fetch(request).then(function (response) {
            if (response.ok && response.type === 'basic') {
                return caches.open(DYNAMIC_CACHE).then(function (cache) {
                    cache.put(request, response.clone());
                    limitCacheSize(DYNAMIC_CACHE, MAX_DYNAMIC_ITEMS);
                    return response;
                });
            }
            return response;
        }).catch(function () {
            return caches.match(request).then(function (cached) {
                return cached || caches.match('/offline.html');
            });
        })
    );
});

self.addEventListener('sync', function (event) {
    if (event.tag === 'sync-data') {
        event.waitUntil(syncPendingData());
    }
});

function syncPendingData() {
    return self.registration.sync.register('sync-data').catch(function () {});
}
