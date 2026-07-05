<?php

namespace App\Livewire\Client\Invoices;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Carbon\Carbon;
use Livewire\Component;

class InvoiceDetail extends Component
{
    public array $invoice = [];

    public array $documents = [];

    public array $schedule = [];

    public array $scheduleSummary = [];

    public function mount(ErpClient $erp, $sale): void
    {
        $token = session('client.access_token');

        try {
            $detail = $erp->propertySale($token, $sale);
            $documents = $erp->propertySaleDocuments($token, $sale);
            $schedules = $erp->propertySalePaymentSchedules($token, $sale);
        } catch (ErpApiException $e) {
            if ($e->statusCode() === 404) {
                abort(404);
            }

            if ($e->statusCode() === 401) {
                session()->forget(['client.access_token', 'client.user']);
            }

            session()->flash('status', $e->statusCode() === 401
                ? 'Your session has expired. Please log in again.'
                : $e->getMessage());
            $this->redirectRoute('client.login');

            return;
        }

        $this->invoice = $this->mapInvoice($detail['data'] ?? []);
        $this->documents = $this->mapDocuments($documents['data'] ?? []);
        $this->schedule = $this->mapSchedule($schedules['data'] ?? []);
        $this->scheduleSummary = $this->buildScheduleSummary($this->schedule);
    }

    protected function mapInvoice(array $sale): array
    {
        $property = $sale['property'] ?? [];
        $units = $sale['units'] ?? [];
        $rent = $sale['rent'] ?? null;
        $status = $this->deriveInvoiceStatus($sale);
        $paymentStatus = $sale['payment_status'] ?? 'pending';

        return [
            'sale_number' => $sale['sale_number'] ?? '',
            'sale_type_label' => $sale['sale_type_label'] ?? 'Property Sale',
            'status' => $status,
            'status_label' => unit_status_label($status),
            'payment_status' => $paymentStatus,
            'payment_status_label' => payment_status_label($paymentStatus),
            'property_name' => $property['name'] ?? '',
            'property_address' => trim('Code '.($property['code'] ?? '').' · '.($property['address'] ?? ''), ' ·'),
            'sale_date' => $this->formatDate($sale['sale_date'] ?? null),
            'contract_date' => $this->formatDate($sale['contract_date'] ?? null),
            'sales_representative' => $sale['sales_representative'] ?? null,
            'notes' => $sale['notes'] ?? null,
            'is_handed_over' => (bool) ($sale['is_handed_over'] ?? false),
            'handover_date' => $this->formatDate($sale['handover_date'] ?? null),
            'rent' => $rent ? [
                'start_date' => $this->formatDate($rent['start_date'] ?? null),
                'end_date' => $this->formatDate($rent['end_date'] ?? null),
                'security_deposit' => bdt_money($rent['security_deposit_amount'] ?? 0),
                'is_renewal' => (bool) ($rent['is_renewal'] ?? false),
            ] : null,
            'units' => array_map(fn (array $unit) => [
                'code' => $unit['code'] ?? '',
                'type' => $unit['type'] ?? 'flat',
                'type_label' => unit_type_label($unit['type'] ?? 'flat'),
                'floor' => $unit['floor'] ?? '',
                'area' => isset($unit['area']) ? number_format((float) $unit['area']).' sqft' : '',
                'price' => bdt_money($unit['price'] ?? 0),
            ], $units),
            'purchase' => [
                ['label' => 'Sale amount', 'value' => bdt_money($sale['sale_amount'] ?? 0)],
                ['label' => 'Discount', 'value' => '− '.bdt_money($sale['discount_amount'] ?? 0), 'discount' => true],
                ['label' => 'Tax', 'value' => bdt_money($sale['tax_amount'] ?? 0)],
                ['label' => 'Down payment', 'value' => bdt_money($sale['down_payment_amount'] ?? 0).' ('.($sale['down_payment_percentage'] ?? 0).'%)'],
            ],
            'net_amount' => bdt_money($sale['net_amount'] ?? 0),
            'paid_amount' => bdt_money($sale['paid_amount'] ?? 0),
            'due_amount' => bdt_money($sale['due_amount'] ?? 0),
        ];
    }

    /**
     * Same derivation as My Properties v2 — the ERP has no single lifecycle
     * field matching Booked/On Installment/Purchased/Handover/Rented.
     */
    protected function deriveInvoiceStatus(array $sale): string
    {
        if (($sale['sale_type'] ?? 'sale') === 'rent') {
            return 'rented';
        }

        if ($sale['is_handed_over'] ?? false) {
            return 'handover';
        }

        return match ($sale['payment_status'] ?? 'pending') {
            'paid' => 'purchased',
            'partial' => 'on_installment',
            default => 'booked',
        };
    }

    protected function mapDocuments(array $docs): array
    {
        return array_map(fn (array $doc) => [
            'name' => $doc['name'] ?? 'Document',
            'meta' => strtoupper($doc['extension'] ?? ''),
            'type' => ($doc['extension'] ?? '') === 'pdf' ? 'pdf' : 'file',
            'url' => $doc['url'] ?? null,
        ], $docs);
    }

    protected function mapSchedule(array $rows): array
    {
        $dueMarked = false;

        return array_map(function (array $row) use (&$dueMarked) {
            $displayStatus = $row['display_status'] ?? $row['status'] ?? 'pending';

            if ($displayStatus === 'paid') {
                $state = 'done';
            } elseif ($displayStatus === 'overdue') {
                // Every overdue row is highlighted, not just the next one — a
                // customer who's missed several installments should see all of them.
                $state = 'overdue';
            } elseif (! $dueMarked) {
                $state = 'due';
                $dueMarked = true;
            } else {
                $state = 'upcoming';
            }

            $dueDate = ! empty($row['due_date']) ? Carbon::parse($row['due_date']) : null;

            return [
                'label' => $row['label'] ?? 'Payment',
                'amount' => bdt_money($row['amount'] ?? 0),
                'state' => $state,
                'display_status' => $displayStatus,
                'meta' => $this->scheduleMeta($state, $displayStatus, $dueDate),
                'transactions' => array_map(fn (array $tx) => [
                    'amount' => bdt_money($tx['amount'] ?? 0),
                    'method' => $tx['method'] ?? null,
                    'datetime' => $tx['datetime'] ?? null,
                ], $row['transactions'] ?? []),
            ];
        }, $rows);
    }

    protected function scheduleMeta(string $state, string $displayStatus, ?Carbon $dueDate): string
    {
        if ($state === 'done') {
            return $dueDate ? 'Paid · '.$dueDate->format('d M Y') : 'Paid';
        }

        if (! $dueDate) {
            return ucfirst($displayStatus);
        }

        return match ($displayStatus) {
            'overdue' => 'Overdue by '.$dueDate->diffForHumans(null, true).' · '.$dueDate->format('d M Y'),
            'partial' => 'Partially paid · Due '.$dueDate->format('d M Y'),
            default => $dueDate->isFuture()
                ? ($state === 'due' ? 'Due in '.$dueDate->diffForHumans(null, true) : 'Upcoming').' · '.$dueDate->format('d M Y')
                : 'Due · '.$dueDate->format('d M Y'),
        };
    }

    protected function buildScheduleSummary(array $schedule): array
    {
        $paid = count(array_filter($schedule, fn (array $row) => $row['state'] === 'done'));
        $overdueCount = count(array_filter($schedule, fn (array $row) => $row['state'] === 'overdue'));

        $next = collect($schedule)->first(fn (array $row) => $row['state'] === 'overdue')
            ?? collect($schedule)->first(fn (array $row) => $row['state'] === 'due');

        return [
            'paid' => $paid.' / '.count($schedule),
            'next_due' => $next['meta'] ?? 'All settled',
            'overdue_count' => $overdueCount,
        ];
    }

    protected function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d M Y') : null;
    }

    public function render()
    {
        return view('livewire.client.invoices.invoice-detail')
            ->layout('layouts.client.client', ['title' => ($this->invoice['property_name'] ?? 'Invoice').' — Star Unity', 'active' => 'invoices']);
    }
}
