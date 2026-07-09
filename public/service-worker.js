self.addEventListener('push', function (event) {
    if (!event.data) {
        return;
    }

    const payload = event.data.json();

    const options = {
        body: payload.body,
        icon: payload.icon,
        image: payload.image,
        badge: payload.badge,
        data: payload.data,
        actions: payload.actions,
        tag: payload.tag,
        requireInteraction: payload.requireInteraction,
        renotify: payload.renotify,
        vibrate: payload.vibrate,
    };

    event.waitUntil(
        self.registration.showNotification(payload.title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const data = event.notification.data || {};
    const actionUrl = data.action_url;
    const recipientId = data.recipient_id;

    event.waitUntil(
        (async () => {
            if (recipientId) {
                try {
                    await fetch(`/notifications/${recipientId}/read`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                    });
                } catch (e) {
                    // Offline or request failed — the in-app list will still
                    // show it as unread; not fatal to the click handler.
                }
            }

            if (!actionUrl) {
                return;
            }

            const windowClients = await clients.matchAll({ type: 'window', includeUncontrolled: true });
            for (const client of windowClients) {
                if (client.url === actionUrl && 'focus' in client) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(actionUrl);
            }
        })()
    );
});

self.addEventListener('pushsubscriptionchange', function (event) {
    event.waitUntil(
        (async () => {
            const applicationServerKey = event.oldSubscription
                ? event.oldSubscription.options.applicationServerKey
                : event.applicationServerKey;

            const subscription = await self.registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey,
            });

            await fetch('/push-subscriptions', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(subscription.toJSON()),
            });
        })()
    );
});
