<?php

namespace App\Livewire\Client\Invoices;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class Invoices extends Component
{
    public array $invoices = [];

    public function mount(ErpClient $erp): void
    {
        try {
            $data = $erp->properties(session('client.access_token'));
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

        $this->invoices = array_map(fn (array $sale) => $this->mapInvoice($sale), $data['data'] ?? []);
    }

    protected function mapInvoice(array $sale): array
    {
        $units = $sale['units'] ?? [];
        $paymentStatus = $sale['payment_status'] ?? 'pending';
        $status = $this->deriveInvoiceStatus($sale);
        $scheduleSummary = $sale['schedule_summary'] ?? [];

        $netAmount = (float) ($sale['net_amount'] ?? 0);
        $paidAmount = (float) ($sale['paid_amount'] ?? 0);
        $progress = $netAmount > 0 ? (int) round(min(100, $paidAmount / $netAmount * 100)) : 0;

        return [
            'id' => $sale['id'],
            'sale_number' => $sale['sale_number'] ?? '',
            'status' => $status,
            'status_label' => unit_status_label($status),
            'variant' => in_array($status, ['purchased', 'rented'], true) ? 'gold' : 'green',
            'payment_status' => $paymentStatus,
            'progress' => $progress,
            'total_price' => bdt_money($sale['net_amount'] ?? 0),
            'outstanding' => bdt_money($sale['due_amount'] ?? 0),
            'property_name' => $sale['property']['name'] ?? '',
            'installments_paid' => $scheduleSummary['paid'] ?? 0,
            'installments_total' => $scheduleSummary['total'] ?? 0,
            'overdue_count' => $scheduleSummary['overdue'] ?? 0,
            'units' => array_map(fn (array $unit) => [
                'code' => $unit['code'] ?? '',
                'type' => unit_type_label($unit['type'] ?? 'flat'),
            ], $units),
        ];
    }

    /**
     * Same derivation as My Properties v2 / Invoice Detail — the ERP has no
     * single lifecycle field matching Booked/On Installment/Purchased/Rented.
     * "Handover" can't be detected here (is_handed_over is detail-only).
     */
    protected function deriveInvoiceStatus(array $sale): string
    {
        if (($sale['sale_type'] ?? 'sale') === 'rent') {
            return 'rented';
        }

        return match ($sale['payment_status'] ?? 'pending') {
            'paid' => 'purchased',
            'partial' => 'on_installment',
            default => 'booked',
        };
    }

    public function render()
    {
        return view('livewire.client.invoices.invoices')
            ->layout('layouts.client.client', ['title' => 'Invoices — Star Unity', 'active' => 'invoices']);
    }
}
