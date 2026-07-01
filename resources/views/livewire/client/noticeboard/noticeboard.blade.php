<div class="screen screen--with-nav">
    <x-client.screen-header title="Noticeboard" />

    <div class="notices-list">
        @foreach ($notices as $notice)
            <div class="notice-card {{ $notice['pinned'] ? 'notice-card--pinned' : '' }}">
                @if ($notice['pinned'])
                    <div class="notice-card__flag">
                        <svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5"><path d="M12 17v5M5 9l7-7 7 7-2 9H7z" fill="none"/></svg>
                        <span class="notice-card__flag-label">PINNED · IMPORTANT</span>
                    </div>
                @else
                    <span class="badge badge--{{ $notice['variant'] }}">{{ $notice['category'] }}</span>
                @endif
                <div class="notice-card__title">{{ $notice['title'] }}</div>
                <div class="notice-card__body">{{ $notice['body'] }}</div>
                <div class="notice-card__date">{{ $notice['date'] }}</div>
            </div>
        @endforeach
    </div>

    <x-client.bottom-nav active="menu" />
</div>
