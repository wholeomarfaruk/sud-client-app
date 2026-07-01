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
    <div class="auth-screen__sub">Enter the phone or email linked to your account. We'll send a secure reset link.</div>

    <div class="form-field">
        <div class="form-field__label">Phone or Email</div>
        <div class="form-field__input-wrap">
            <svg class="form-field__icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
            <input type="text" name="login" class="form-field__input" placeholder="Phone or email" value="+880 1712-345678">
        </div>
    </div>

    <a href="{{ route('client.login') }}" class="btn btn--primary" style="margin-top:26px">Send reset link</a>

    <div class="auth-screen__info-box">
        Still stuck? Call <b>+880 9610-000111</b> — Sat–Thu, 10am–6pm.
    </div>
</div>
