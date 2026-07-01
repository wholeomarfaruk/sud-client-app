<div class="auth-screen">
    <img src="{{ asset('images/client/sud-logo-black.png') }}" alt="Star Unity Development" class="auth-screen__logo">

    <div class="auth-screen__h1" style="margin-top:38px">Welcome back</div>
    <div class="auth-screen__sub">Log in to manage your properties &amp; payments.</div>

    @if (session('status'))
        <div style="color:#d92d20;font-size:13px;margin-bottom:12px">{{ session('status') }}</div>
    @endif

    <form wire:submit="submit">
        @error('login')
            <div style="color:#d92d20;font-size:13px;margin-bottom:12px">{{ $message }}</div>
        @enderror

        <div class="form-field">
            <div class="form-field__label">Phone or Email</div>
            <div class="form-field__input-wrap">
                <svg class="form-field__icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input type="text" wire:model="login" class="form-field__input" placeholder="Phone or email" autocomplete="username">
            </div>
        </div>

        <x-client.password-field label="Password" name="password" wire:model="password" />

        <a href="{{ route('client.forgot-password') }}" class="form-field__hint">Forgot password?</a>

        <button type="submit" class="btn btn--primary" style="margin-top:24px" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="submit">Log In</span>
            <span wire:loading wire:target="submit">Logging in&hellip;</span>
        </button>
    </form>

    <div class="auth-screen__spacer"></div>
    <div class="auth-screen__footnote">Account is created by Star Unity. <br>No public sign-up.</div>
</div>
