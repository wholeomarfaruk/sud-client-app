// Customer Portal — Alpine store for the shared slide-out drawer.
// Livewire v3 bundles Alpine itself, so this only registers app-specific state.
document.addEventListener('alpine:init', () => {
    Alpine.store('drawer', { open: false });
});

// Web Push ====================================================
// Registers public/service-worker.js and manages the browser push
// subscription. The VAPID public key is baked into the page via a
// <meta name="vapid-public-key"> tag (see layouts/client/client.blade.php) —
// it's public by design, no need to fetch it from an API.
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}

function pushSupported() {
    return 'serviceWorker' in navigator && 'PushManager' in window;
}

function vapidPublicKey() {
    return document.querySelector('meta[name="vapid-public-key"]')?.getAttribute('content') || null;
}

async function postSubscription(subscription) {
    await fetch('/push-subscriptions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(subscription.toJSON()),
    });
}

// Silent re-sync: if the browser already holds a subscription (from a
// previous visit), make sure the server still has it. Harmless to re-POST —
// the backend dedupes by endpoint. Never prompts for permission.
export async function syncExistingPushSubscription() {
    if (!pushSupported() || !vapidPublicKey()) {
        return;
    }

    try {
        const registration = await navigator.serviceWorker.register('/service-worker.js');
        const subscription = await registration.pushManager.getSubscription();

        if (subscription) {
            await postSubscription(subscription);
        }
    } catch (e) {
        console.error('Push subscription sync failed:', e);
    }
}

// Deliberate opt-in: call only from a real user action (a click on an
// "Enable notifications" control), never automatically on page load —
// browsers auto-suppress permission prompts fired on load, and repeated
// denials can permanently block future prompts.
export async function enablePushNotifications() {
    if (!pushSupported()) {
        return { status: 'unsupported' };
    }

    const vapidKey = vapidPublicKey();
    if (!vapidKey) {
        return { status: 'unsupported' };
    }

    try {
        const registration = await navigator.serviceWorker.register('/service-worker.js');
        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                return { status: 'denied' };
            }

            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidKey),
            });
        }

        await postSubscription(subscription);

        return { status: 'granted' };
    } catch (e) {
        console.error('Push subscription failed:', e);

        return { status: 'error' };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    syncExistingPushSubscription();
});

window.enablePushNotifications = enablePushNotifications;
// Web Push ====================================================END
