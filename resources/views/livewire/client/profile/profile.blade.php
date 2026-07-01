<div class="screen">
    <div class="profile-hero">
        <div class="profile-hero__top">
            <button type="button" x-data @click="$store.drawer.open = true" class="profile-hero__icon-btn" aria-label="Open menu">
                <svg viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h16M3 11h16M3 16h11"/></svg>
            </button>
            <span class="profile-hero__title">Profile</span>
            <button type="button" class="profile-hero__icon-btn" aria-label="Edit profile">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
            </button>
        </div>
        <div class="profile-hero__user">
            <div class="profile-avatar">{{ initials($name) }}</div>
            <div class="profile-hero__name">{{ $name }}</div>
            <div class="profile-hero__id">Customer ID · {{ $customerId }}</div>
        </div>
    </div>

    <div class="profile-body">
        <div class="profile-section-label">Contact</div>
        <div class="profile-list">
            @foreach ($contact as $i => $row)
                <div class="profile-list__row">
                    @if ($row['label'] === 'Phone')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
                    @elseif ($row['label'] === 'Email')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    @endif
                    <div>
                        <div class="profile-list__label">{{ $row['label'] }}</div>
                        <div class="profile-list__value">{{ $row['value'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="profile-section-label">Identity</div>
        <div class="profile-list">
            <div class="profile-list__row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M15 8h3M15 12h3M5 16h14"/></svg>
                <div>
                    <div class="profile-list__label">NID</div>
                    <div class="profile-list__value">{{ $nid }}</div>
                </div>
                <span class="badge badge--{{ $kyc['variant'] }} u-ml-auto">{{ $kyc['label'] }}</span>
            </div>
        </div>

        <a href="{{ route('client.change-password') }}" class="btn btn--outline" style="margin-top:22px">Change Password</a>
    </div>

    <x-client.bottom-nav active="menu" />
</div>
