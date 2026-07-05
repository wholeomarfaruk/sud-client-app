<?php

namespace App\Livewire\Client\Properties;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class MyProperties extends Component
{
    public array $units = [];

    public function mount(ErpClient $erp): void
    {
        try {
            $data = $erp->myPropertiesUnitWise(session('client.access_token'));
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

        $this->units = array_map(fn (array $row) => $this->mapUnit($row), $data['data'] ?? []);
    }

    protected function mapUnit(array $row): array
    {
        $unit = $row['unit'] ?? [];
        $type = $unit['type'] ?? 'flat';
        $paymentStatus = $row['payment_status'] ?? 'pending';

        return [
            'id' => $row['id'] ?? null,
            'sale_id' => $row['sale_id'] ?? null,
            'sale_number' => $row['sale_number'] ?? '',
            'property_name' => $row['property']['name'] ?? '',
            'unit_code' => $unit['code'] ?? '',
            'unit_type' => $type,
            'unit_type_variant' => unit_type_variant($type),
            'unit_type_label' => unit_type_label($type),
            'floor_label' => $unit['floor'] ?? '',
            'area' => isset($unit['area']) ? number_format((float) $unit['area']).' sqft' : '',
            'unit_status' => $this->deriveUnitStatus($row),
            'unit_status_label' => unit_status_label($this->deriveUnitStatus($row)),
            'payment_status' => $paymentStatus,
            'payment_status_label' => payment_status_label($paymentStatus),
            'price' => bdt_money($unit['price'] ?? 0),
        ];
    }

    /**
     * The ERP doesn't expose a single lifecycle field matching the design's
     * Booked/On Installment/Purchased/Handover/Rented pill — it's derived from
     * sale_type + payment_status. "Handover" can't be detected from the list
     * payload (is_handed_over is only present on the detail endpoint).
     */
    protected function deriveUnitStatus(array $row): string
    {
        if (($row['sale_type'] ?? 'sale') === 'rent') {
            return 'rented';
        }

        return match ($row['payment_status'] ?? 'pending') {
            'paid' => 'purchased',
            'partial' => 'on_installment',
            default => 'booked',
        };
    }

    public function render()
    {
        return view('livewire.client.properties.my-properties')
            ->layout('layouts.client.client', ['title' => 'My Properties — Star Unity', 'active' => 'my-properties']);
    }
}
