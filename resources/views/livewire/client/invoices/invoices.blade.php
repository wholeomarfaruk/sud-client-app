<div class="screen screen--with-nav">
    <x-client.screen-header title="Invoices" />

    <div class="invoices-list">
        @foreach ($invoices as $invoice)
            <a href="{{ route('client.invoice-detail', $invoice['id']) }}" class="invoice-card">
                <div class="invoice-card__banner invoice-card__banner--{{ $invoice['variant'] }}">
                    <span class="unit-status-pill unit-status-pill--{{ $invoice['status'] }}">{{ $invoice['status_label'] }}</span>
                    <span class="invoice-card__watermark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                    </span>
                    <div>
                        <div class="invoice-card__title">{{ $invoice['property_name'] }}</div>
                        <div class="invoice-card__meta">
                            {{ count($invoice['units']) === 1 ? 'Unit '.$invoice['units'][0]['code'].' · '.$invoice['units'][0]['type'] : count($invoice['units']).' units' }}
                        </div>
                    </div>
                </div>
                <div class="invoice-card__body">
                    <div class="invoice-card__head-row">
                        <span class="invoice-card__invoice-no">Invoice {{ $invoice['sale_number'] }}</span>
                        <span class="payment-status-chip payment-status-chip--{{ $invoice['payment_status'] }}">
                            <span class="payment-status-chip__dot"></span>{{ payment_status_label($invoice['payment_status']) }}
                        </span>
                    </div>

                    @if (count($invoice['units']) > 1)
                        <div class="invoice-card__units">
                            @foreach ($invoice['units'] as $unit)
                                <span class="invoice-card__unit-chip">{{ $unit['code'] }} · {{ $unit['type'] }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="invoice-card__progress-row">
                        <span>Payment progress</span>
                        <span class="invoice-card__progress-pct invoice-card__progress-pct--{{ $invoice['variant'] }}">{{ $invoice['progress'] }}%</span>
                    </div>
                    <div class="progress {{ $invoice['variant'] === 'gold' ? 'progress--gold' : '' }}">
                        <div class="progress__fill" style="width:{{ $invoice['progress'] }}%"></div>
                    </div>

                    <div class="invoice-card__totals">
                        <div>
                            <div class="invoice-card__total-label">Total price</div>
                            <div class="invoice-card__total-value">{{ $invoice['total_price'] }}</div>
                        </div>
                        <div>
                            <div class="invoice-card__total-label">Outstanding</div>
                            <div class="invoice-card__total-value invoice-card__total-value--right invoice-card__total-value--outstanding">{{ $invoice['outstanding'] }}</div>
                        </div>
                    </div>

                    <div class="invoice-card__installments">
                        <span>Installments</span>
                        <span class="invoice-card__installments-value {{ $invoice['overdue_count'] > 0 ? 'invoice-card__installments-value--danger' : '' }}">
                            {{ $invoice['installments_paid'] }} / {{ $invoice['installments_total'] }} paid
                            @if ($invoice['overdue_count'] > 0)
                                · {{ $invoice['overdue_count'] }} overdue
                            @endif
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <x-client.bottom-nav active="menu" />
</div>
