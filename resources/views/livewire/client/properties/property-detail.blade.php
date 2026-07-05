<div class="screen">
    @php $imageCount = max(count($unit['images']), 1); @endphp

    <div class="unit-hero" x-data="{ active: 0, total: {{ $imageCount }} }">
        <div class="unit-hero__slides" @scroll.debounce.75ms="active = Math.round($event.target.scrollLeft / $event.target.clientWidth)">
            @forelse ($unit['images'] as $image)
                <div class="unit-hero__slide unit-hero__slide--{{ $unit['unit_type_variant'] }}">
                    <img src="{{ $image }}" alt="{{ $unit['property_name'] }}">
                </div>
            @empty
                <div class="unit-hero__slide unit-hero__slide--{{ $unit['unit_type_variant'] }}">
                    <svg width="90" height="90" viewBox="0 0 24 24" fill="none" stroke-width="1.2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                </div>
            @endforelse
        </div>

        <div class="unit-hero__top">
            <a href="{{ route('client.my-properties') }}" class="unit-hero__back" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <span class="unit-hero__counter" x-text="(active + 1) + ' / ' + total"></span>
        </div>

        @if ($imageCount > 1)
            <div class="unit-hero__dots">
                @foreach (range(0, $imageCount - 1) as $i)
                    <span class="unit-hero__dot" :class="{ 'is-active': active === {{ $i }} }"></span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="unit-sheet">
        <div class="unit-sheet__head">
            <div class="unit-sheet__title-row">
                <div>
                    <div class="unit-sheet__title">{{ $unit['property_name'] }}</div>
                    <div class="unit-sheet__subtitle">{{ $unit['unit_code'] }} · {{ $unit['property_address'] }}</div>
                </div>
                <span class="unit-status-pill unit-status-pill--{{ $unit['unit_status'] }}">{{ $unit['unit_status_label'] }}</span>
            </div>
            <div class="unit-sheet__type-row">
                <span class="unit-type-text unit-type-text--{{ $unit['unit_type_variant'] }}">Unit {{ $unit['unit_code'] }} · {{ $unit['unit_type_label'] }} · {{ $unit['floor_label'] }}</span>
                <span class="payment-status-chip payment-status-chip--{{ $unit['payment_status'] }}">
                    <span class="payment-status-chip__dot"></span>{{ $unit['payment_status_label'] }}
                </span>
            </div>
        </div>

        <div class="unit-section">
            <div class="unit-quick-info">
                <div class="unit-quick-info__item">
                    <div class="unit-quick-info__label">Area</div>
                    <div class="unit-quick-info__value">{{ $unit['area'] }}</div>
                </div>
                <div class="unit-quick-info__item">
                    <div class="unit-quick-info__label">Floor</div>
                    <div class="unit-quick-info__value">{{ $unit['floor_label'] }}</div>
                </div>
                <div class="unit-quick-info__item">
                    <div class="unit-quick-info__label">Total area</div>
                    <div class="unit-quick-info__value">{{ $unit['total_area'] }}</div>
                </div>
            </div>

            <div class="unit-section-title">Invoice — purchase summary</div>
            <div class="purchase-table">
                @foreach ($unit['purchase'] as $row)
                    <div class="purchase-table__row">
                        <span class="purchase-table__label">{{ $row['label'] }}</span>
                        <span class="purchase-table__value {{ ($row['discount'] ?? false) ? 'purchase-table__value--discount' : '' }}">{{ $row['value'] }}</span>
                    </div>
                @endforeach
                <div class="purchase-table__row purchase-table__row--net">
                    <span class="purchase-table__label">Net amount</span>
                    <span class="purchase-table__value">{{ $unit['net_amount'] }}</span>
                </div>
            </div>

            <div class="unit-quick-info" style="margin-top:12px">
                <div class="unit-quick-info__item">
                    <div class="unit-quick-info__label">Paid</div>
                    <div class="unit-quick-info__value">{{ $unit['paid_amount'] }}</div>
                </div>
                <div class="unit-quick-info__item">
                    <div class="unit-quick-info__label">Due</div>
                    <div class="unit-quick-info__value">{{ $unit['due_amount'] }}</div>
                </div>
            </div>

            <a href="{{ route('client.payment-history') }}" class="btn btn--outline" style="margin-top:14px">View full payment history</a>

            <div class="unit-section-title">Documents</div>
            @forelse ($unit['documents'] as $doc)
                <div class="list-row" style="margin-bottom:9px">
                    <span class="list-row__icon {{ ($doc['type'] ?? '') === 'pdf' ? 'list-row__icon--pdf' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15h6"/></svg>
                    </span>
                    <div class="list-row__body">
                        <div class="list-row__title">{{ $doc['name'] }}</div>
                        <div class="list-row__meta">{{ $doc['meta'] ?? '' }}</div>
                    </div>
                    <a href="{{ $doc['url'] ?? '#' }}" class="list-row__action" aria-label="Download {{ $doc['name'] }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    </a>
                </div>
            @empty
                <div class="unit-empty-state">No documents uploaded yet.</div>
            @endforelse

            <div class="unit-section-title">Gallery</div>
            @if (count($unit['images']))
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    @foreach ($unit['images'] as $image)
                        <img src="{{ $image }}" alt="{{ $unit['property_name'] }}" style="width:100%;height:120px;object-fit:cover;border-radius:12px">
                    @endforeach
                </div>
            @else
                <div class="unit-empty-state">No gallery images yet.</div>
            @endif
        </div>
    </div>

    <x-client.bottom-nav active="menu" />
</div>
