import { precacheAndRoute } from 'workbox-precaching';
import { registerRoute } from 'workbox-routing';
import { NetworkFirst, StaleWhileRevalidate } from 'workbox-strategies';

self.skipWaiting();
self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

precacheAndRoute(self.__WB_MANIFEST);

registerRoute(
    ({ request }) => request.mode === 'navigate',
    new NetworkFirst({
        cacheName: 'pages',
        networkTimeoutSeconds: 4,
        plugins: [{ handlerDidError: async () => caches.match('/offline.html') }],
    }),
);

registerRoute(
    ({ request }) => ['image', 'font'].includes(request.destination),
    new StaleWhileRevalidate({ cacheName: 'assets' }),
);

self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    const title = data.title ?? 'Finlora';
    const options = {
        body: data.body,
        icon: data.icon ?? '/icon-192.png',
        badge: data.badge ?? '/icon-192.png',
        data: { url: data.data?.url ?? '/dashboard' },
    };
    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url ?? '/dashboard';
    event.waitUntil(
        self.clients.matchAll({ type: 'window' }).then((windowClients) => {
            const existing = windowClients.find((client) => client.url.includes(url));
            if (existing) return existing.focus();
            return self.clients.openWindow(url);
        }),
    );
});
