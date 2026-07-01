<div class="screen screen--with-nav">
    <x-client.screen-header title="Installments">
        <x-slot:trailing>
            <div class="property-select" x-data="{ open: false, selected: '{{ $selectedProperty }}' }">
                <button type="button" class="screen-header__action" @click="open = !open">
                    <span x-text="selected"></span> ▾
                </button>
                <div class="property-select__menu" x-show="open" x-cloak @click.outside="open = false">
                    @foreach ($properties as $property)
                        <button type="button" class="property-select__option" @click="selected = '{{ $property }}'; open = false">
                            {{ $property }}
                        </button>
                    @endforeach
                </div>
            </div>
        </x-slot:trailing>
    </x-client.screen-header>

    <div class="screen__body installments-body">
        <div class="installment-summary">
            <div>
                <div class="installment-summary__label">Paid</div>
                <div class="installment-summary__value installment-summary__value--accent">{{ $summary['paid'] }}</div>
            </div>
            <div>
                <div class="installment-summary__label">Next due</div>
                <div class="installment-summary__value installment-summary__value--due">{{ $summary['next_due'] }}</div>
            </div>
            <div>
                <div class="installment-summary__label">Remaining</div>
                <div class="installment-summary__value">{{ $summary['remaining'] }}</div>
            </div>
        </div>

        <div class="timeline">
            @foreach ($schedule as $i => $step)
                <div class="timeline__row">
                    <div class="timeline__rail">
                        <span class="timeline__node timeline__node--{{ $step['state'] }}">
                            @if ($step['state'] === 'done')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            @endif
                        </span>
                        @if (!$loop->last)
                            <span class="timeline__connector {{ $step['state'] === 'done' ? '' : 'timeline__connector--muted' }}"></span>
                        @endif
                    </div>

                    <div class="timeline__content">
                        @if ($step['state'] === 'due')
                            <div class="timeline__due-card">
                                <div class="timeline__row-head">
                                    <span class="timeline__title">Installment #{{ $step['n'] }}</span>
                                    <span class="timeline__amount timeline__amount--accent">{{ $step['amount'] }}</span>
                                </div>
                                <div class="timeline__due-label">{{ $step['meta'] }}</div>
                                <button type="button" class="timeline__pay-btn">Pay now</button>
                            </div>
                        @else
                            <div class="timeline__row-head">
                                <span class="timeline__title {{ $step['state'] === 'upcoming' ? 'timeline__title--muted' : '' }}">Installment #{{ $step['n'] }}</span>
                                <span class="timeline__amount {{ $step['state'] === 'upcoming' ? 'timeline__amount--muted' : '' }}">{{ $step['amount'] }}</span>
                            </div>
                            <div class="timeline__meta">{{ $step['meta'] }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-client.bottom-nav active="menu" />
</div>
