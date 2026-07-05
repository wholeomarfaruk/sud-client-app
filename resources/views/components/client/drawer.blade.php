{{-- Shared slide-out drawer (§6 "Slide-out Drawer"). Mounted once in the client
     layout; visibility is driven by the global Alpine `drawer` store
     (registered in resources/js/client.js) so any screen can open it via the
     bottom-nav "Menu" tab. --}}
@props(['active' => null])

@php
    $sidebarName = data_get($sidebar, 'user.name', 'Md. Rafiqul Islam');
    $sidebarCustomerId = data_get($sidebar, 'customer.customer_id', 'SUD-10472');
    $sidebarUnreadNotifications = data_get($sidebar, 'unread_notifications', 3);
@endphp

<div x-data x-show="$store.drawer.open" x-cloak
     x-effect="document.documentElement.style.overflow = $store.drawer.open ? 'hidden' : ''">
    <div class="drawer__scrim"
         x-show="$store.drawer.open"
         x-transition:enter="drawer-fade" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="drawer-fade" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="$store.drawer.open = false"></div>

    <nav class="drawer__panel"
         x-show="$store.drawer.open"
         x-transition:enter="drawer-slide" x-transition:enter-start="drawer-slide-start" x-transition:enter-end="drawer-slide-end"
         x-transition:leave="drawer-slide" x-transition:leave-start="drawer-slide-end" x-transition:leave-end="drawer-slide-start"
         @keydown.escape.window="$store.drawer.open = false">

        <div class="drawer__header">
            <img src="{{ asset('images/client/sud-logo-white.png') }}" alt="Star Unity Development" class="drawer__logo">

            <div class="drawer__user">
                <div class="drawer__avatar">{{ initials($sidebarName) }}</div>
                <div>
                    <div class="drawer__name">{{ $sidebarName }}</div>
                    <div class="drawer__id">ID: {{ $sidebarCustomerId }}</div>
                </div>
            </div>
        </div>

        <div class="drawer__nav">
            <a href="{{ route('dashboard') }}" class="drawer__nav-item {{ $active === 'dashboard' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('client.profile') }}" class="drawer__nav-item {{ $active === 'profile' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                <span>My Profile</span>
            </a>
            <a href="{{ route('client.my-properties') }}" class="drawer__nav-item {{ $active === 'my-properties' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                <span>My Properties</span>
            </a>
            <a href="{{ route('client.invoices') }}" class="drawer__nav-item {{ $active === 'invoices' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15h6M9 11h1M14 15h1"/></svg>
                <span>Invoices</span>
            </a>
            <a href="{{ route('client.payment-history') }}" class="drawer__nav-item {{ $active === 'payment-history' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                <span>Payment History</span>
            </a>
            <a href="{{ route('client.offers') }}" class="drawer__nav-item {{ $active === 'offers' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v9H4v-9M2 7h20v5H2zM12 22V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7"/></svg>
                <span>Offers</span>
            </a>
            <a href="{{ route('client.news') }}" class="drawer__nav-item {{ $active === 'news' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
                <span>News &amp; Articles</span>
            </a>
            <a href="{{ route('client.notifications') }}" class="drawer__nav-item {{ $active === 'notifications' ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                <span>Notifications</span>
                @if ($sidebarUnreadNotifications > 0)
                    <span class="drawer__badge">{{ $sidebarUnreadNotifications }}</span>
                @endif
            </a>
        </div>

        <div class="drawer__footer">
            <a href="tel:+8809610000111" class="drawer__support-link">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
                Call us · 09610-000111
            </a>
            <a href="mailto:support@starunitydevelopment.com" class="drawer__support-link">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                Email support
            </a>
            <a href="https://wa.me/8809610000111" target="_blank" rel="noopener" class="drawer__support-link">
                <span class="drawer__whatsapp-glyph">
                    <svg viewBox="0 0 24 24" fill="#0F6B2E"><path d="M17.5 14.4c-.3-.15-1.7-.84-2-.93-.27-.1-.46-.15-.65.14-.19.29-.74.93-.91 1.12-.17.19-.34.21-.63.07-.29-.15-1.22-.45-2.32-1.43-.86-.77-1.44-1.71-1.6-2-.17-.29-.02-.45.12-.59.13-.13.29-.34.43-.51.15-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.07-.14-.65-1.57-.89-2.15-.23-.56-.47-.48-.65-.49h-.55c-.19 0-.5.07-.76.36-.26.29-1 .98-1 2.38s1.02 2.76 1.17 2.95c.14.19 2.01 3.07 4.87 4.31.68.29 1.21.47 1.62.6.68.22 1.3.19 1.79.11.55-.08 1.7-.69 1.94-1.36.24-.67.24-1.24.17-1.36-.07-.12-.26-.19-.55-.34Z"/><path d="M12 2a10 10 0 0 0-8.52 15.27L2 22l4.85-1.42A10 10 0 1 0 12 2Zm0 18.2a8.16 8.16 0 0 1-4.16-1.14l-.3-.18-2.88.84.77-2.8-.2-.3A8.2 8.2 0 1 1 12 20.2Z"/></svg>
                </span>
                WhatsApp Chat
            </a>
            <a href="https://starunitydevelopment.com" target="_blank" rel="noopener" class="drawer__support-link">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/></svg>
                Visit Website
            </a>
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <button type="submit" class="drawer__support-link drawer__logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</div>
