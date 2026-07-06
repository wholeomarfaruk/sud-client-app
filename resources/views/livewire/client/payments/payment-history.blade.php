<div class="screen screen--with-nav" x-data="{ filter: 'all' }">
    <x-client.screen-header title="Payment History" />

    <div class="screen__body">
        <div class="payment-summary">
            <div class="payment-summary__label">Total paid (all properties)</div>
            <div class="payment-summary__amount">{{ $totalPaid }}</div>
            <div class="payment-summary__meta">{{ $summaryMeta }}</div>
        </div>

        @if (count($properties))
            <div class="filter-chips">
                <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'all' }" @click="filter = 'all'">All</button>
                @foreach ($properties as $property)
                    <button type="button" class="filter-chip" :class="{ 'is-active': filter === '{{ $property['id'] }}' }" @click="filter = '{{ $property['id'] }}'">{{ $property['name'] }}</button>
                @endforeach
            </div>
        @endif

        @forelse ($months as $month)
            <div class="payments-month-label">{{ $month['label'] }}</div>
            <div class="payments-list">
                @foreach ($month['rows'] as $row)
                    <div class="payment-row" x-data="{ open: false }" x-show="filter === 'all' || filter === '{{ $row['property_id'] }}'">
                        <button type="button" class="list-row payment-row__toggle" @click="open = !open">
                            <span class="list-row__icon {{ $row['status'] === 'partial' ? 'list-row__icon--gold' : '' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </span>
                            <div class="list-row__body">
                                <div class="list-row__title">{{ $row['title'] }}</div>
                                <div class="list-row__meta">{{ $row['meta'] }}</div>
                            </div>
                            <div class="list-row__trailing">
                                <div class="list-row__amount">{{ $row['amount'] }}</div>
                                <div class="list-row__status">{{ $row['status_label'] }}</div>
                            </div>
                            @if (count($row['transactions']))
                                <span class="payment-row__chevron" :class="{ 'is-open': open }">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </span>
                            @endif
                        </button>

                        @if (count($row['transactions']))
                            <div class="payment-row__transactions" x-show="open" x-cloak>
                                @foreach ($row['transactions'] as $tx)
                                    <div class="transaction-row">
                                        <div class="transaction-row__head">
                                            <span>{{ $tx['amount'] }} · {{ $tx['method'] }}</span>
                                            <span>{{ $tx['datetime'] ?? '—' }}</span>
                                        </div>
                                        @if ($tx['reference_no'] || $tx['payer_name'] || $tx['notes'])
                                            <div class="transaction-row__meta">
                                                {{ collect([
                                                    $tx['reference_no'] ? 'Ref: '.$tx['reference_no'] : null,
                                                    $tx['payer_name'] ? 'Payer: '.$tx['payer_name'] : null,
                                                    $tx['notes'],
                                                ])->filter()->implode(' · ') }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @empty
            <div class="unit-empty-state">No payments recorded yet.</div>
        @endforelse
    </div>

    <x-client.bottom-nav active="menu" />
</div>
