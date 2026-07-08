{{-- 6-digit OTP entry: auto-advance, backspace-back, paste-split, resend countdown.
     "Verify & continue" submits the code to the Otp Livewire component's verify() action. --}}
@props(['otpError' => ''])

<div x-data="otpInput()">
    <div class="otp__cells">
        <template x-for="(d, i) in digits" :key="i">
            <input
                type="text" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code"
                class="otp__cell"
                :class="{ 'is-filled': digits[i] !== '' }"
                :value="digits[i] === '' ? String.fromCharCode(8203) : digits[i]"
                @keydown.backspace="onBackspace($event, i)"
                @keydown="onKeydown($event, i)"
                @beforeinput="onBeforeinput($event)"
                @input="onInput($event, i)"
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
                _backspaceFlag: false,
                get complete() { return this.digits.every(d => d !== ''); },
                init() {
                    this.tick();
                    this.focusCell(0);
                },
                tick() {
                    const t = setInterval(() => {
                        if (this.countdown > 0) this.countdown--; else clearInterval(t);
                    }, 1000);
                },
                focusCell(index) {
                    this.$nextTick(() => {
                        const cells = this.$root.querySelectorAll('.otp__cell');
                        if (cells[index]) cells[index].focus();
                    });
                },
                // Validation layer 1 (original): reliable on desktop keyboards.
                onBackspace(e, i) {
                    if (!this.digits[i] && i > 0) {
                        e.target.previousElementSibling.focus();
                    }
                },
                // Validation layer 2: records any signal that looks like a backspace/delete
                // action, since some mobile IMEs report keyCode 229 / key "Unidentified"
                // and skip the ".backspace" modifier used by onBackspace above.
                markBackspace(e) {
                    if (e.key === 'Backspace' || e.keyCode === 8 || e.inputType === 'deleteContentBackward') {
                        this._backspaceFlag = true;
                    }
                },
                // Validation layer 3: stops stray non-digit characters from a physical
                // keyboard before they ever render (arrows/tab/ctrl combos pass through
                // since e.key.length > 1 for those).
                blockNonDigit(e) {
                    if (e.key.length === 1 && !/\d/.test(e.key) && !e.ctrlKey && !e.metaKey) {
                        e.preventDefault();
                    }
                },
                onKeydown(e, i) {
                    this.markBackspace(e);
                    this.blockNonDigit(e);
                },
                onBeforeinput(e) {
                    this.markBackspace(e);
                },
                // Validation layer 4 (primary, mobile-safe): the :value binding renders an
                // invisible zero-width character instead of '' when a cell is logically
                // empty, so there is always real content to delete. That guarantees this
                // input event fires reliably even on mobile keyboards that silently drop
                // keydown for Backspace on an apparently-empty field.
                onInput(e, i) {
                    const wasBackspace = this._backspaceFlag || e.inputType === 'deleteContentBackward';
                    this._backspaceFlag = false;

                    const raw = e.target.value.replace(/\D/g, '');

                    if (raw.length > 1) {
                        // Whole code arrived at once: iOS QuickType suggestion, Android
                        // SMS autofill, or a paste that landed here instead of onPaste.
                        this.fillFrom(0, raw);
                        return;
                    }

                    const hadDigit = this.digits[i] !== '';
                    this.digits[i] = raw.slice(-1);

                    if (this.digits[i] && i < 5) {
                        e.target.nextElementSibling.focus();
                        return;
                    }
                    if (!this.digits[i] && wasBackspace && !hadDigit && i > 0) {
                        e.target.previousElementSibling.focus();
                    }
                },
                onPaste(e) {
                    this.fillFrom(0, e.clipboardData.getData('text') || '');
                },
                fillFrom(start, str) {
                    const chars = str.replace(/\D/g, '').slice(0, 6 - start).split('');
                    chars.forEach((c, idx) => { this.digits[start + idx] = c; });
                    if (!this.complete) {
                        this.focusCell(Math.min(start + chars.length, 5));
                    }
                },
                async resend() {
                    await this.$wire.resend();
                    if (!this.$wire.otpError) {
                        this.digits = ['', '', '', '', '', ''];
                        this.countdown = 48;
                        this.tick();
                        this.focusCell(0);
                    }
                },
            };
        }
    </script>
    @endpush
@endonce
