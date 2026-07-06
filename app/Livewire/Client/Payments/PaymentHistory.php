<?php

namespace App\Livewire\Client\Payments;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Carbon\Carbon;
use Livewire\Component;

class PaymentHistory extends Component
{
    public string $totalPaid = '৳ 0';

    public string $summaryMeta = '';

    public array $properties = [];

    public array $months = [];

    public function mount(ErpClient $erp): void
    {
        try {
            $data = $erp->paymentHistory(session('client.access_token'));
        } catch (ErpApiException $e) {
            if ($e->statusCode() === 401) {
                session()->forget(['client.access_token', 'client.user']);
            }

            session()->flash('status', $e->statusCode() === 401
                ? 'Your session has expired. Please log in again.'
                : $e->getMessage());
            $this->redirectRoute('client.login');

            return;
        }

        $payload = $data['data'] ?? [];
        $summary = $payload['summary'] ?? [];

        $this->totalPaid = bdt_money($summary['total_paid'] ?? 0);
        $this->summaryMeta = $this->buildSummaryMeta($summary);
        $this->properties = array_map(fn (array $property) => [
            'id' => $property['id'],
            'name' => $property['name'] ?? '',
        ], $payload['properties'] ?? []);
        $this->months = array_map(fn (array $group) => $this->mapGroup($group), $payload['groups'] ?? []);
    }

    protected function buildSummaryMeta(array $summary): string
    {
        $count = $summary['transaction_count'] ?? 0;
        $label = $count === 1 ? 'transaction' : 'transactions';
        $since = $summary['since'] ?? null;

        return $since
            ? "{$count} {$label} · since ".Carbon::parse($since)->format('M Y')
            : "{$count} {$label}";
    }

    protected function mapGroup(array $group): array
    {
        return [
            'label' => $group['month_label'] ?? '',
            'rows' => array_map(fn (array $item) => $this->mapItem($item), $group['items'] ?? []),
        ];
    }

    protected function mapItem(array $item): array
    {
        $status = $item['display_status'] ?? $item['status'] ?? 'paid';
        $paidDate = $item['paid_date'] ?? null;

        $metaParts = array_filter([
            $paidDate ? Carbon::parse($paidDate)->format('d M Y') : null,
            $item['method'] ?? null,
            $item['property']['name'] ?? null,
        ]);

        return [
            'title' => $item['label'] ?? 'Payment',
            'meta' => implode(' · ', $metaParts),
            'amount' => bdt_money($item['paid_amount'] ?? 0),
            'status' => $status,
            'status_label' => payment_status_label($status),
            'property_id' => $item['property']['id'] ?? null,
            'transactions' => array_map(fn (array $tx) => $this->mapTransaction($tx), $item['transactions'] ?? []),
        ];
    }

    protected function mapTransaction(array $tx): array
    {
        return [
            'amount' => bdt_money($tx['amount'] ?? 0),
            'method' => $tx['method'] ?? '—',
            'reference_no' => $tx['reference_no'] ?? null,
            'payer_name' => $tx['payer_name'] ?? null,
            'notes' => $tx['notes'] ?? null,
            'datetime' => ! empty($tx['datetime']) ? Carbon::parse($tx['datetime'])->format('d M Y, h:i A') : null,
        ];
    }

    public function render()
    {
        return view('livewire.client.payments.payment-history')
            ->layout('layouts.client.client', ['title' => 'Payment History — Star Unity', 'active' => 'payment-history']);
    }
}
