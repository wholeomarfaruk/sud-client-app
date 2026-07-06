<div class="auth-screen auth-screen--with-back">
    <a href="{{ route('client.login') }}" class="auth-screen__back">
        <span class="auth-screen__back-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </span>
        <span class="auth-screen__back-label">Back to login</span>
    </a>

    <div class="auth-screen__icon-tile auth-screen__icon-tile--accent">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>

    <div class="auth-screen__h1">Reset your password</div>
    <div class="auth-screen__sub">Enter the phone or email linked to your account. We'll send a 6-digit code to set a new password.</div>

    <form wire:submit="submit">
        @error('login')
            <div style="color:#d92d20;font-size:13px;margin-bottom:12px">{{ $message }}</div>
        @enderror

        <div class="form-field">
            <div class="form-field__label">Phone or Email</div>
            <div class="form-field__input-wrap">
                <svg class="form-field__icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                <input type="text" wire:model.blur="login" class="form-field__input" placeholder="Phone or email" autocomplete="username">
            </div>
        </div>

        <button type="submit" class="btn btn--primary" style="margin-top:26px" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="submit">Send reset code</span>
            <span wire:loading wire:target="submit">Sending&hellip;</span>
        </button>
    </form>

    <div class="auth-screen__info-box">
        Still stuck? Call <b>+880 9610-000111</b> — Sat–Thu, 10am–6pm.
    </div>
</div>
