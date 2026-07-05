<div class="screen screen--with-nav" x-data="{ filter: 'all' }">
    <x-client.screen-header title="My Properties" />

    <div style="display:flex;gap:8px;padding:0 18px 10px">
        <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'all' }" @click="filter = 'all'">All units</button>
        <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'flat' }" @click="filter = 'flat'">Flat</button>
        <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'shop' }" @click="filter = 'shop'">Shop</button>
        <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'parking' }" @click="filter = 'parking'">Parking</button>
    </div>

    <div class="units-list">
        @forelse ($units as $unit)
            <a href="{{ route('client.property-detail', [$unit['sale_id'], $unit['id']]) }}" class="unit-card" x-show="filter === 'all' || filter === '{{ $unit['unit_type'] }}'">
                <div class="unit-card__thumb unit-card__thumb--{{ $unit['unit_type_variant'] }}">
                    @switch($unit['unit_type'])
                        @case('shop')
                        @case('showroom')
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke-width="1.4"><path d="M3 9.5 5 4h14l2 5.5M3 9.5v9A1.5 1.5 0 0 0 4.5 20h15a1.5 1.5 0 0 0 1.5-1.5v-9M3 9.5h18M9 20v-5h6v5"/></svg>
                            @break
                        @case('parking')
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke-width="1.4"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M7 15h2M15 15h2"/></svg>
                            @break
                        @case('community_center')
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke-width="1.4"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M8 4v16"/></svg>
                            @break
                        @default
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke-width="1.4"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                    @endswitch
                </div>
                <div class="unit-card__body">
                    <div class="unit-card__head">
                        <span class="unit-card__title">{{ $unit['property_name'] }}</span>
                        <span class="unit-status-pill unit-status-pill--{{ $unit['unit_status'] }}">{{ $unit['unit_status_label'] }}</span>
                    </div>
                    <div class="unit-type-text unit-type-text--{{ $unit['unit_type_variant'] }}">Unit {{ $unit['unit_code'] }} · {{ $unit['unit_type_label'] }}</div>
                    <div class="unit-card__meta-row">
                        <span class="unit-card__meta">{{ $unit['floor_label'] }} · {{ $unit['area'] }}</span>
                        <span class="payment-status-chip payment-status-chip--{{ $unit['payment_status'] }}">
                            <span class="payment-status-chip__dot"></span>{{ $unit['payment_status_label'] }}
                        </span>
                    </div>
                    <div class="unit-card__foot">
                        <span class="unit-card__price">{{ $unit['price'] }}</span>
                        <span class="unit-card__sale-number">{{ $unit['sale_number'] }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="unit-empty-state">No properties found yet.</div>
        @endforelse
    </div>

    <x-client.bottom-nav active="menu" />
</div>
