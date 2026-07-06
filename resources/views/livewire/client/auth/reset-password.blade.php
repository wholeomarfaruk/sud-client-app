<div class="auth-screen auth-screen--with-back">
    <a href="{{ route('client.forgot-password') }}" class="auth-screen__back">
        <span class="auth-screen__back-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </span>
        <span class="auth-screen__back-label">Back</span>
    </a>

    <div class="auth-screen__icon-tile auth-screen__icon-tile--green">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>

    <div class="auth-screen__h1">Set new password</div>
    <div class="auth-screen__sub">Enter the 6-digit code we sent to the phone or email on file, then choose a new password.</div>

    <x-client.password-field label="New password" name="password" placeholder="••••••••••" wire:model="password" />
    <x-client.password-field label="Confirm new password" name="password_confirmation" placeholder="••••••••••" wire:model="password_confirmation" />
    @error('password')
        <div style="color:#d92d20;font-size:13px;margin-top:-8px;margin-bottom:12px">{{ $message }}</div>
    @enderror

    <x-client.otp-input :otp-error="$otpError" />
</div>
