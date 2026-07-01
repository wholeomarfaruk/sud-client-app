<div class="screen screen--with-nav" x-data="{ filter: 'all' }">
    <x-client.screen-header title="Payment History" />

    <div class="screen__body">
        <div class="payment-summary">
            <div class="payment-summary__label">Total paid (all properties)</div>
            <div class="payment-summary__amount">{{ $totalPaid }}</div>
            <div class="payment-summary__meta">{{ $summaryMeta }}</div>
        </div>

        <div class="filter-chips">
            <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'all' }" @click="filter = 'all'">All</button>
            <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'heights' }" @click="filter = 'heights'">Heights</button>
            <button type="button" class="filter-chip" :class="{ 'is-active': filter === 'greenview' }" @click="filter = 'greenview'">Greenview</button>
        </div>

        @foreach ($months as $month)
            <div class="payments-month-label">{{ $month['label'] }}</div>
            <div class="payments-list">
                @foreach ($month['rows'] as $row)
                    <div class="list-row" x-show="filter === 'all' || filter === '{{ $row['property'] }}'">
                        <span class="list-row__icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </span>
                        <div class="list-row__body">
                            <div class="list-row__title">{{ $row['title'] }}</div>
                            <div class="list-row__meta">{{ $row['meta'] }}</div>
                        </div>
                        <div class="list-row__trailing">
                            <div class="list-row__amount">{{ $row['amount'] }}</div>
                            <div class="list-row__status">PAID</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <x-client.bottom-nav active="menu" />
</div>
