{{-- Shared article/offer detail layout — hero image (or gradient placeholder)
     + title/date + rich-text body. Used by News Detail and Offer Detail. --}}
@props(['title', 'date' => null, 'image' => null, 'back', 'badge' => null])

<div class="screen">
    <div class="content-hero">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="content-hero__image">
        @else
            <div class="content-hero__placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
            </div>
        @endif

        <a href="{{ $back }}" class="content-hero__back" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>

        @if ($badge)
            <span class="badge badge--solid-accent content-hero__badge">{{ $badge }}</span>
        @endif
    </div>

    <div class="content-sheet">
        <div class="content-sheet__title">{{ $title }}</div>
        @if ($date)
            <div class="content-sheet__date">{{ $date }}</div>
        @endif
        <div class="content-sheet__body">
            {{ $slot }}
        </div>
    </div>

    <x-client.bottom-nav active="menu" />
</div>
