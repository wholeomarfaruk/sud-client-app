<div class="screen screen--with-nav"
     x-data="{ newPassword: '' }"
     @pf-input.window="if ($event.detail.name === 'new_password') newPassword = $event.detail.value">
    <div class="change-password-header">
        <a href="{{ route('client.profile') }}" class="screen-header__icon-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <span class="screen-header__title">Change Password</span>
    </div>

    <div class="change-password-body">
        <x-client.password-field label="Current password" name="current_password" sm />
        <x-client.password-field label="New password" name="new_password" placeholder="••••••••••" sm />
        <x-client.password-field label="Confirm new password" name="new_password_confirmation" placeholder="••••••••••" sm />

        <div class="rule-checklist">
            <div class="rule-checklist__item" :class="{ 'is-met': newPassword.length >= 8 }">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                At least 8 characters
            </div>
            <div class="rule-checklist__item" :class="{ 'is-met': /[A-Z]/.test(newPassword) && /[0-9]/.test(newPassword) }">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                One uppercase &amp; one number
            </div>
            <div class="rule-checklist__item" :class="{ 'is-met': /[^A-Za-z0-9]/.test(newPassword) }">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="12" r="9"/></svg>
                One special character
            </div>
        </div>

        <button type="button" class="btn btn--primary" style="margin-top:20px">Update password</button>
    </div>

    <x-client.bottom-nav active="menu" />
</div>
