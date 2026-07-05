{{-- 6-digit OTP entry: auto-advance, backspace-back, paste-split, resend countdown.
     "Verify & continue" submits the code to the Otp Livewire component's verify() action. --}}
@props(['otpError' => ''])

<div x-data="otpInput()">
    <div class="otp__cells">
        <template x-for="(d, i) in digits" :key="i">
            <input
                type="text" inputmode="numeric" maxlength="1"
                class="otp__cell"
                :class="{ 'is-filled': digits[i] !== '' }"
                x-model="digits[i]"
                @input="onInput($event, i)"
                @keydown.backspace="onBackspace($event, i)"
                @paste.prevent="onPaste($event)"
            >
        </template>
    </div>

    <button type="button" class="btn btn--primary" style="margin-top:26px"
            :disabled="!complete"
            wire:loading.attr="disabled" wire:target="verify"
            @click="complete && $wire.verify(digits.join(''))">
        <span wire:loading.remove wire:target="verify">Verify &amp; continue</span>
        <span wire:loading wire:target="verify">Verifying&hellip;</span>
    </button>

    @if ($otpError !== '')
        <div style="color:#d92d20;font-size:13px;margin-top:12px">{{ $otpError }}</div>
    @endif

    <p class="otp__resend">
        Didn't get the code?
        <template x-if="countdown > 0">
            <span class="otp__timer" x-text="`Resend in 0:${String(countdown).padStart(2,'0')}`"></span>
        </template>
        <template x-if="countdown === 0">
            <button type="button" class="otp__resend-btn"
                    wire:loading.attr="disabled" wire:target="resend"
                    @click="resend()">
                <span wire:loading.remove wire:target="resend">Resend code</span>
                <span wire:loading wire:target="resend">Sending&hellip;</span>
            </button>
        </template>
    </p>
</div>

@once
    @push('scripts')
    <script>
        function otpInput() {
            return {
                digits: ['', '', '', '', '', ''],
                countdown: 48,
                get complete() { return this.digits.every(d => d !== ''); },
                init() { this.tick(); },
                tick() {
                    const t = setInterval(() => {
                        if (this.countdown > 0) this.countdown--; else clearInterval(t);
                    }, 1000);
                },
                onInput(e, i) {
                    const v = e.target.value.replace(/\D/g, '');
                    this.digits[i] = v.slice(-1);
                    if (v && i < 5) e.target.parentElement.children[i + 1].focus();
                },
                onBackspace(e, i) {
                    if (!this.digits[i] && i > 0) {
                        e.target.parentElement.children[i - 1].focus();
                    }
                },
                onPaste(e) {
                    const chars = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6).split('');
                    chars.forEach((c, i) => this.digits[i] = c);
                },
                async resend() {
                    await this.$wire.resend();
                    if (!this.$wire.otpError) {
                        this.digits = ['', '', '', '', '', ''];
                        this.countdown = 48;
                        this.tick();
                    }
                },
            };
        }
    </script>
    @endpush
@endonce
