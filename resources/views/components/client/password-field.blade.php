{{-- Password input with show/hide eye toggle (Alpine). Focus ring comes from
     `.form-field__input-wrap:focus-within` in _form-field.scss — no JS needed
     for the focused look. --}}
@props(['label', 'name', 'placeholder' => '••••••••', 'sm' => false])

<div class="form-field" x-data="{ show: false }">
    <div class="form-field__label">{{ $label }}</div>
    <div class="form-field__input-wrap {{ $sm ? 'form-field__input-wrap--sm' : '' }}">
        <input
            :type="show ? 'text' : 'password'"
            name="{{ $name }}"
            id="{{ $name }}"
            class="form-field__input"
            placeholder="{{ $placeholder }}"
            autocomplete="new-password"
            style="letter-spacing: 3px"
            @input="$dispatch('pf-input', { name: '{{ $name }}', value: $event.target.value })"
            {{ $attributes->whereStartsWith('wire') }}
        >
        <button type="button" class="form-field__toggle" @click="show = !show" aria-label="Toggle password visibility">
            <svg x-show="!show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg x-show="show" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.53 13.53 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
        </button>
    </div>
</div>
