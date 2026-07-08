<div class="auth-screen auth-screen--with-back">
    <a href="{{ route('client.login') }}" class="auth-screen__back">
        <span class="auth-screen__back-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </span>
        <span class="auth-screen__back-label">Back to login</span>
    </a>

    <div class="auth-screen__icon-tile auth-screen__icon-tile--green">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>

    <div class="auth-screen__h1">Enter verification code</div>
    <div class="auth-screen__sub">We sent a 6-digit code to<br><strong>{{ $destination }}</strong> via {{ $channel === 'email' ? 'email' : 'SMS' }}.</div>

    <x-client.otp-input :otp-error="$otpError" />

    <div class="auth-screen__spacer auth-screen__spacer--sm"></div>

    <div class="otp__warning">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <span class="otp__warning-text">Never share this code. Star Unity staff will never ask for your OTP.</span>
    </div>
</div>
