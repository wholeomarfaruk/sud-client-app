<div class="screen">
    <div class="property-hero">
        <div class="property-hero__top">
            <a href="{{ route('client.my-properties') }}" class="property-hero__icon-btn" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <button type="button" class="property-hero__icon-btn" aria-label="More options">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </button>
        </div>
        <div class="property-hero__title">{{ $propertyData['name'] }}</div>
        <div class="property-hero__meta">{{ $propertyData['address'] }}</div>
    </div>

    <div class="property-sheet">
        <div class="property-sheet__chips">
            <div class="fact-chip">
                <div class="fact-chip__label">Size</div>
                <div class="fact-chip__value">{{ $propertyData['size'] }}</div>
            </div>
            <div class="fact-chip">
                <div class="fact-chip__label">Floor</div>
                <div class="fact-chip__value">{{ $propertyData['floor'] }}</div>
            </div>
            <div class="fact-chip">
                <div class="fact-chip__label">Facing</div>
                <div class="fact-chip__value">{{ $propertyData['facing'] }}</div>
            </div>
        </div>

        <div class="property-sheet__docs-head">
            <span class="property-sheet__docs-title">Documents</span>
            <span class="property-sheet__docs-count">{{ count($documents) }} files</span>
        </div>

        <div class="property-sheet__docs-list">
            @foreach ($documents as $doc)
                <div class="list-row">
                    <span class="list-row__icon {{ $doc['type'] === 'pdf' ? 'list-row__icon--pdf' : '' }}">
                        @if ($doc['type'] === 'pdf')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15h6"/></svg>
                        @endif
                    </span>
                    <div class="list-row__body">
                        <div class="list-row__title">{{ $doc['name'] }}</div>
                        <div class="list-row__meta" style="margin-top:1px">{{ $doc['meta'] }}</div>
                    </div>
                    <a href="#" class="list-row__action" aria-label="Download {{ $doc['name'] }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <x-client.bottom-nav active="menu" />
</div>
