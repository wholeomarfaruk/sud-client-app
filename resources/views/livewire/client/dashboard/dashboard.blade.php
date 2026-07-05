<div class="screen screen--with-nav">
    <header class="dashboard-header">
        <div class="dashboard-header__top">
            <button type="button" x-data @click="$store.drawer.open = true" class="dashboard-header__icon-btn" aria-label="Open menu">
                <svg viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h16M3 11h16M3 16h11"/></svg>
            </button>
            <img src="{{ asset('images/client/sud-logo-white.png') }}" alt="Star Unity Development" class="dashboard-header__logo">
            <a href="{{ route('client.notifications') }}" class="dashboard-header__bell" aria-label="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                @if ($unreadNotifications > 0)
                    <span class="dashboard-header__bell-badge">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
                @endif
            </a>
        </div>

        <div class="dashboard-header__greeting">Assalamu Alaikum,</div>
        <div class="dashboard-header__name">{{ $customerName }}</div>

        <div class="stat-strip">
            <div class="stat-strip__cell">
                <div class="stat-strip__value">{{ $stats['properties'] }}</div>
                <div class="stat-strip__label">Properties</div>
            </div>
            <div class="stat-strip__divider"></div>
            <div class="stat-strip__cell">
                <div class="stat-strip__value">{{ $stats['total_paid'] }}</div>
                <div class="stat-strip__label">Total paid</div>
            </div>
            <div class="stat-strip__divider"></div>
            <div class="stat-strip__cell">
                <div class="stat-strip__value stat-strip__value--accent">{{ $stats['outstanding'] }}</div>
                <div class="stat-strip__label">Outstanding</div>
            </div>
        </div>
    </header>

    @if ($nextInstallment)
        <div class="next-installment">
            <div class="next-installment__row">
                <span class="next-installment__label">Next installment due</span>
                <span class="next-installment__pill">{{ $nextInstallment['due_in'] }}</span>
            </div>
            <div class="next-installment__amount">{{ $nextInstallment['amount'] }}</div>
            <div class="next-installment__meta">{{ $nextInstallment['meta'] }}</div>
            <a href="{{ route('client.invoices') }}" class="btn btn--outline next-installment__cta">
                View installment details
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>
    @endif

    <div class="quick-actions" style="padding-left:18px;padding-right:18px">
        <a href="{{ route('client.invoices') }}" class="quick-actions__item">
            <span class="quick-actions__icon quick-actions__icon--accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            </span>
            <span class="quick-actions__label">Pay now</span>
        </a>
        <a href="{{ route('client.invoices') }}" class="quick-actions__item">
            <span class="quick-actions__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </span>
            <span class="quick-actions__label">Schedule</span>
        </a>
        <a href="{{ route('client.my-properties') }}" class="quick-actions__item">
            <span class="quick-actions__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15h6"/></svg>
            </span>
            <span class="quick-actions__label">Documents</span>
        </a>
        <a href="{{ route('client.my-properties') }}" class="quick-actions__item">
            <span class="quick-actions__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
            </span>
            <span class="quick-actions__label">Properties</span>
        </a>
        <a href="{{ route('client.payment-history') }}" class="quick-actions__item">
            <span class="quick-actions__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </span>
            <span class="quick-actions__label">History</span>
        </a>
        <a href="tel:+8809610000111" class="quick-actions__item">
            <span class="quick-actions__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
            </span>
            <span class="quick-actions__label">Support</span>
        </a>
        <a href="{{ route('client.profile') }}" class="quick-actions__item">
            <span class="quick-actions__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
            </span>
            <span class="quick-actions__label">Profile</span>
        </a>
    </div>

    <div class="dashboard-section">
        <div class="dashboard-section__head">
            <span class="dashboard-section__title">Offers for you</span>
            <a href="{{ route('client.offers') }}" class="dashboard-section__link">See all</a>
        </div>
    </div>

    <div class="dashboard-section" style="padding-top:10px">
        <x-client.offer-carousel :count="count($offers)">
            @foreach ($offers as $offer)
                <a href="{{ route('client.offers') }}" class="offer-card-mini offer-card-mini--{{ $offer['variant'] }} carousel__item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                </a>
            @endforeach
        </x-client.offer-carousel>
    </div>

    <x-client.bottom-nav active="dashboard" />
</div>
