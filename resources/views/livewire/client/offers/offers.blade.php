<div class="screen screen--with-nav">
    <x-client.screen-header title="Offers" />

    <div class="offers-list">
        @if ($loadError)
            <div class="unit-empty-state">{{ $loadError }}</div>
        @elseif (count($offers) === 0)
            <div class="unit-empty-state">No offers available right now.</div>
        @else
            @foreach ($offers as $offer)
                <a href="{{ route('client.offer-detail', $offer['id']) }}" class="offer-card">
                    <div class="offer-card__banner offer-card__banner--{{ $offer['variant'] }}" @if($offer['image']) style="background-image:url('{{ $offer['image'] }}');background-size:cover;background-position:center" @endif>
                        @if ($offer['is_featured'])
                            <span class="offer-card__tag offer-card__tag--{{ $offer['variant'] }}">FEATURED</span>
                        @endif
                        @if (! $offer['image'])
                            @if ($offer['variant'] === 'accent')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M20 12v8H4v-8M2 7h20v5H2zM12 22V7"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                            @endif
                        @endif
                    </div>
                    <div class="offer-card__body">
                        <div class="offer-card__title">{{ $offer['title'] }}</div>
                        <div class="offer-card__desc">{{ $offer['desc'] }}</div>
                        <div class="offer-card__meta">
                            <span class="offer-card__valid">{{ $offer['date'] }}</span>
                            <span class="offer-card__link">View details →</span>
                        </div>
                    </div>
                </a>
            @endforeach

            @if ($hasMore)
                <button type="button" class="btn btn--outline" wire:click="loadMore" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="loadMore">Load more</span>
                    <span wire:loading wire:target="loadMore">Loading&hellip;</span>
                </button>
            @endif
        @endif
    </div>

    <x-client.bottom-nav active="offers" />
</div>
