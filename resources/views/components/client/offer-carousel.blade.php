{{-- Horizontal scroll-snap carousel + active dot indicator (Dashboard "Offers
     for you"). Active dot is derived from scroll position — no item data is
     hard-coded here, items are passed in via the default slot. --}}
@props(['count'])

<div class="carousel" x-data="{ active: 0 }" @scroll.debounce.75ms="active = Math.min({{ $count }} - 1, Math.round($el.scrollLeft / 308))">
    {{ $slot }}
    <div class="carousel__spacer"></div>
</div>

@if ($count > 1)
    <div class="carousel-dots">
        @foreach (range(0, $count - 1) as $i)
            <span class="carousel-dots__dot" :class="{ 'is-active': active === {{ $i }} }"></span>
        @endforeach
    </div>
@endif
