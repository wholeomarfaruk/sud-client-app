{{-- Hamburger + title + bell (or custom trailing slot) row used on most
     authenticated screens. Pass `back` to swap the hamburger for a back arrow,
     and slot `trailing` to override the default notification bell. --}}
@props(['title', 'back' => null, 'bell' => true])

<div class="screen-header">
    @if ($back)
        <a href="{{ $back }}" class="screen-header__icon-btn" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
    @else
        <button type="button" x-data @click="$store.drawer.open = true" class="screen-header__icon-btn" aria-label="Open menu">
            <svg viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h16M3 11h16M3 16h11"/></svg>
        </button>
    @endif

    <span class="screen-header__title">{{ $title }}</span>

    @isset($trailing)
        {{ $trailing }}
    @else
        @if ($bell)
            <a href="{{ route('client.notifications') }}" class="screen-header__bell" aria-label="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                <span class="screen-header__bell-dot"></span>
            </a>
        @else
            <span style="width:22px"></span>
        @endif
    @endisset
</div>
