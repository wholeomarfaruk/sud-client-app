<div class="screen">
    <div class="property-hero">
        <div class="property-hero__top">
            <a href="{{ route('client.invoices') }}" class="property-hero__icon-btn" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
        </div>
        <div class="property-hero__title">{{ $invoice['property_name'] }}</div>
        <div class="property-hero__meta">Invoice {{ $invoice['sale_number'] }} · {{ $invoice['property_address'] }}</div>
    </div>

    <div class="property-sheet">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
            <span class="unit-status-pill unit-status-pill--{{ $invoice['status'] }}">{{ $invoice['status_label'] }}</span>
            <span class="payment-status-chip payment-status-chip--{{ $invoice['payment_status'] }}">
                <span class="payment-status-chip__dot"></span>{{ $invoice['payment_status_label'] }}
            </span>
        </div>

        <div class="property-sheet__chips" style="margin-top:14px">
            <div class="fact-chip">
                <div class="fact-chip__label">Sale type</div>
                <div class="fact-chip__value">{{ $invoice['sale_type_label'] }}</div>
            </div>
            <div class="fact-chip">
                <div class="fact-chip__label">Sale date</div>
                <div class="fact-chip__value">{{ $invoice['sale_date'] ?? '—' }}</div>
            </div>
            <div class="fact-chip">
                <div class="fact-chip__label">Units</div>
                <div class="fact-chip__value">{{ count($invoice['units']) }}</div>
            </div>
        </div>

        @if ($invoice['sales_representative'] || $invoice['notes'] || $invoice['is_handed_over'] || $invoice['rent'])
            <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
                @if ($invoice['sales_representative'])
                    <div style="display:flex;justify-content:space-between"><span class="unit-card__meta">Sales representative</span><span class="purchase-table__value">{{ $invoice['sales_representative'] }}</span></div>
                @endif
                @if ($invoice['is_handed_over'])
                    <div style="display:flex;justify-content:space-between"><span class="unit-card__meta">Handed over</span><span class="purchase-table__value">{{ $invoice['handover_date'] ?? '—' }}</span></div>
                @endif
                @if ($invoice['rent'])
                    <div style="display:flex;justify-content:space-between"><span class="unit-card__meta">Rent period</span><span class="purchase-table__value">{{ $invoice['rent']['start_date'] }} – {{ $invoice['rent']['end_date'] ?? 'ongoing' }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span class="unit-card__meta">Security deposit</span><span class="purchase-table__value">{{ $invoice['rent']['security_deposit'] }}</span></div>
                @endif
                @if ($invoice['notes'])
                    <div><span class="unit-card__meta">{{ $invoice['notes'] }}</span></div>
                @endif
            </div>
        @endif

        <div class="unit-section-title">Purchase amount</div>
        <div class="purchase-table">
            @foreach ($invoice['purchase'] as $row)
                <div class="purchase-table__row">
                    <span class="purchase-table__label">{{ $row['label'] }}</span>
                    <span class="purchase-table__value {{ ($row['discount'] ?? false) ? 'purchase-table__value--discount' : '' }}">{{ $row['value'] }}</span>
                </div>
            @endforeach
            <div class="purchase-table__row purchase-table__row--net">
                <span class="purchase-table__label">Net amount</span>
                <span class="purchase-table__value">{{ $invoice['net_amount'] }}</span>
            </div>
        </div>

        <div class="unit-quick-info" style="margin-top:12px">
            <div class="unit-quick-info__item">
                <div class="unit-quick-info__label">Paid</div>
                <div class="unit-quick-info__value">{{ $invoice['paid_amount'] }}</div>
            </div>
            <div class="unit-quick-info__item">
                <div class="unit-quick-info__label">Due</div>
                <div class="unit-quick-info__value">{{ $invoice['due_amount'] }}</div>
            </div>
        </div>

        <div class="property-sheet__docs-head">
            <span class="property-sheet__docs-title">Units</span>
            <span class="property-sheet__docs-count">{{ count($invoice['units']) }} units</span>
        </div>
        <div class="property-sheet__docs-list">
            @foreach ($invoice['units'] as $unit)
                <div class="list-row">
                    <span class="list-row__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                    </span>
                    <div class="list-row__body">
                        <div class="list-row__title">Unit {{ $unit['code'] }} · {{ $unit['type_label'] }}</div>
                        <div class="list-row__meta">{{ $unit['floor'] }} · {{ $unit['area'] }}</div>
                    </div>
                    <div class="list-row__trailing">
                        <div class="list-row__amount">{{ $unit['price'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="property-sheet__docs-head">
            <span class="property-sheet__docs-title">Documents</span>
            <span class="property-sheet__docs-count">{{ count($documents) }} files</span>
        </div>
        <div class="property-sheet__docs-list">
            @forelse ($documents as $doc)
                <div class="list-row">
                    <span class="list-row__icon {{ $doc['type'] === 'pdf' ? 'list-row__icon--pdf' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                    </span>
                    <div class="list-row__body">
                        <div class="list-row__title">{{ $doc['name'] }}</div>
                        <div class="list-row__meta" style="margin-top:1px">{{ $doc['meta'] }}</div>
                    </div>
                    <a href="{{ $doc['url'] ?? '#' }}" class="list-row__action" aria-label="Download {{ $doc['name'] }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    </a>
                </div>
            @empty
                <div class="unit-empty-state">No documents uploaded yet.</div>
            @endforelse
        </div>

        <div class="unit-section-title">Payment schedule</div>
        <div class="installment-summary" style="margin-top:9px">
            <div>
                <div class="installment-summary__label">Paid</div>
                <div class="installment-summary__value installment-summary__value--accent">{{ $scheduleSummary['paid'] ?? '0 / 0' }}</div>
            </div>
            <div>
                <div class="installment-summary__label">{{ ($scheduleSummary['overdue_count'] ?? 0) > 0 ? 'Overdue (' . $scheduleSummary['overdue_count'] . ')' : 'Next due' }}</div>
                <div class="installment-summary__value {{ ($scheduleSummary['overdue_count'] ?? 0) > 0 ? 'installment-summary__value--danger' : 'installment-summary__value--due' }}">{{ $scheduleSummary['next_due'] ?? 'All settled' }}</div>
            </div>
            <div>
                <div class="installment-summary__label">Remaining</div>
                <div class="installment-summary__value">{{ $invoice['due_amount'] }}</div>
            </div>
        </div>

        <div class="timeline">
            @forelse ($schedule as $step)
                <div class="timeline__row">
                    <div class="timeline__rail">
                        <span class="timeline__node timeline__node--{{ $step['state'] }}">
                            @if ($step['state'] === 'done')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            @elseif ($step['state'] === 'overdue')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4M12 16h.01"/><path d="m10.29 3.86-8.48 14.14A1 1 0 0 0 2.66 19.5h18.68a1 1 0 0 0 .85-1.5L13.71 3.86a1 1 0 0 0-1.72 0Z"/></svg>
                            @endif
                        </span>
                        @if (!$loop->last)
                            <span class="timeline__connector {{ $step['state'] === 'done' ? '' : 'timeline__connector--muted' }}"></span>
                        @endif
                    </div>

                    <div class="timeline__content">
                        @if ($step['state'] === 'overdue')
                            <div class="timeline__overdue-card">
                                <div class="timeline__row-head">
                                    <span class="timeline__title">{{ $step['label'] }}</span>
                                    <span class="timeline__amount timeline__amount--danger">{{ $step['amount'] }}</span>
                                </div>
                                <div class="timeline__overdue-label">{{ $step['meta'] }}</div>
                            </div>
                        @elseif ($step['state'] === 'due')
                            <div class="timeline__due-card">
                                <div class="timeline__row-head">
                                    <span class="timeline__title">{{ $step['label'] }}</span>
                                    <span class="timeline__amount timeline__amount--accent">{{ $step['amount'] }}</span>
                                </div>
                                <div class="timeline__due-label">{{ $step['meta'] }}</div>
                            </div>
                        @else
                            <div class="timeline__row-head">
                                <span class="timeline__title {{ $step['state'] === 'upcoming' ? 'timeline__title--muted' : '' }}">{{ $step['label'] }}</span>
                                <span class="timeline__amount {{ $step['state'] === 'upcoming' ? 'timeline__amount--muted' : '' }}">{{ $step['amount'] }}</span>
                            </div>
                            <div class="timeline__meta">{{ $step['meta'] }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="unit-empty-state">No payment schedule yet.</div>
            @endforelse
        </div>
    </div>

    <x-client.bottom-nav active="menu" />
</div>
