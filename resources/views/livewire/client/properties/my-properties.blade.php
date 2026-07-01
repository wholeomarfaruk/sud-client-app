<div class="screen screen--with-nav">
    <x-client.screen-header title="My Properties" />

    <div class="properties-list">
        @foreach ($properties as $property)
            <a href="{{ route('client.property-detail', $property['id']) }}" class="property-card">
                <div class="property-card__banner property-card__banner--{{ $property['variant'] }}">
                    <span class="property-card__status-pill property-card__status-pill--{{ $property['variant'] }}">{{ $property['status'] }}</span>
                    <span class="property-card__watermark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                    </span>
                    <div>
                        <div class="property-card__title">{{ $property['name'] }}</div>
                        <div class="property-card__meta">{{ $property['meta'] }}</div>
                    </div>
                </div>
                <div class="property-card__body">
                    <div class="property-card__progress-row">
                        <span>Payment progress</span>
                        <span class="property-card__progress-pct property-card__progress-pct--{{ $property['variant'] }}">{{ $property['progress'] }}%</span>
                    </div>
                    <div class="progress {{ $property['variant'] === 'gold' ? 'progress--gold' : '' }}">
                        <div class="progress__fill" style="width:{{ $property['progress'] }}%"></div>
                    </div>
                    @if ($property['total_price'])
                        <div class="property-card__totals">
                            <div>
                                <div class="property-card__total-label">Total price</div>
                                <div class="property-card__total-value">{{ $property['total_price'] }}</div>
                            </div>
                            <div>
                                <div class="property-card__total-label">Outstanding</div>
                                <div class="property-card__total-value property-card__total-value--right property-card__total-value--outstanding">{{ $property['outstanding'] }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <x-client.bottom-nav active="menu" />
</div>
