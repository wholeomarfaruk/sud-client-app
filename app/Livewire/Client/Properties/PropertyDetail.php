<?php

namespace App\Livewire\Client\Properties;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class PropertyDetail extends Component
{
    public array $unit = [];

    public function mount(ErpClient $erp, $sale, $saleUnit): void
    {
        try {
            $data = $erp->myPropertiesUnitWiseDetail(session('client.access_token'), $sale, $saleUnit);
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

        abort_if(! isset($data['data']), 404);

        $this->unit = $this->mapUnit($data['data']);
    }

    protected function mapUnit(array $row): array
    {
        $unit = $row['unit'] ?? [];
        $property = $row['property'] ?? [];
        $pricing = $row['pricing'] ?? [];
        $type = $unit['type'] ?? 'flat';
        $paymentStatus = $row['payment_status'] ?? 'pending';
        $unitStatus = $this->deriveUnitStatus($row);

        return [
            'sale_number' => $row['sale_number'] ?? '',
            'property_name' => $property['name'] ?? '',
            'property_address' => trim('Code '.($property['code'] ?? '').' · '.($property['address'] ?? ''), ' ·'),
            'unit_code' => $unit['code'] ?? '',
            'unit_type' => $type,
            'unit_type_variant' => unit_type_variant($type),
            'unit_type_label' => unit_type_label($type),
            'floor_label' => $unit['floor'] ?? '',
            'area' => isset($unit['area']) ? number_format((float) $unit['area']).' sqft' : '—',
            'total_area' => isset($property['total_area']) ? number_format((float) $property['total_area']).' sqft' : '—',
            'unit_status' => $unitStatus,
            'unit_status_label' => unit_status_label($unitStatus),
            'payment_status' => $paymentStatus,
            'payment_status_label' => payment_status_label($paymentStatus),
            'images' => array_values(array_filter($row['images'] ?? [])),
            'purchase' => [
                ['label' => 'Sale amount', 'value' => bdt_money($pricing['sale_amount'] ?? 0)],
                ['label' => 'Discount', 'value' => '− '.bdt_money($pricing['discount_amount'] ?? 0), 'discount' => true],
                ['label' => 'Tax', 'value' => bdt_money($pricing['tax_amount'] ?? 0)],
                ['label' => 'Service charge', 'value' => bdt_money($pricing['service_charge'] ?? 0)],
                ['label' => 'Utility charge', 'value' => bdt_money($pricing['utility_charge'] ?? 0)],
                ['label' => 'Down payment', 'value' => ($pricing['down_payment_percentage'] ?? 0).'%'],
            ],
            'net_amount' => bdt_money($pricing['net_amount'] ?? 0),
            'paid_amount' => bdt_money($row['paid_amount'] ?? 0),
            'due_amount' => bdt_money($row['due_amount'] ?? 0),
            'documents' => array_map(fn (array $doc) => [
                'name' => $doc['name'] ?? 'Document',
                'meta' => strtoupper($doc['extension'] ?? ''),
                'type' => ($doc['extension'] ?? '') === 'pdf' ? 'pdf' : 'file',
                'url' => $doc['url'] ?? null,
            ], $row['documents'] ?? []),
        ];
    }

    /**
     * The ERP doesn't expose a single lifecycle field matching the design's
     * Booked/On Installment/Purchased/Handover/Rented pill — it's derived from
     * sale_type + is_handed_over + payment_status.
     */
    protected function deriveUnitStatus(array $row): string
    {
        if (($row['sale_type'] ?? 'sale') === 'rent') {
            return 'rented';
        }

        if ($row['is_handed_over'] ?? false) {
            return 'handover';
        }

        return match ($row['payment_status'] ?? 'pending') {
            'paid' => 'purchased',
            'partial' => 'on_installment',
            default => 'booked',
        };
    }

    public function render()
    {
        return view('livewire.client.properties.property-detail')
            ->layout('layouts.client.client', ['title' => ($this->unit['property_name'] ?? 'Unit').' — Star Unity', 'active' => 'my-properties']);
    }
}
