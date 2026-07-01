<?php

namespace App\Livewire\Client\Profile;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';

    public string $customerId = '';

    public array $contact = [];

    public string $nid = 'Not submitted yet';

    public array $kyc = ['label' => 'PENDING', 'variant' => 'accent'];

    public function mount(ErpClient $erp): void
    {
        try {
            $data = $erp->profile(session('client.access_token'));
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

        $customer = $data['customer'] ?? [];

        $this->name = $customer['name'] ?? ($data['user']['name'] ?? '');
        $this->customerId = $customer['customer_id'] ?? '';

        $this->contact = array_values(array_filter([
            $customer['phone'] ?? null ? ['label' => 'Phone', 'value' => $customer['phone']] : null,
            $customer['email'] ?? null ? ['label' => 'Email', 'value' => $customer['email']] : null,
            $this->formatAddress($customer) ? ['label' => 'Address', 'value' => $this->formatAddress($customer)] : null,
        ]));

        $this->nid = $customer['doc_no'] ?? 'Not submitted yet';
        $this->kyc = $this->mapKycStatus($customer['kyc_status'] ?? 'pending');
    }

    protected function formatAddress(array $customer): ?string
    {
        $parts = array_filter([
            $customer['address'] ?? null,
            $customer['district'] ?? null,
            $customer['division'] ?? null,
            $customer['postal_code'] ?? null,
        ]);

        return $parts ? implode(', ', $parts) : null;
    }

    protected function mapKycStatus(string $status): array
    {
        return match ($status) {
            'verified' => ['label' => 'VERIFIED', 'variant' => 'green'],
            'rejected' => ['label' => 'REJECTED', 'variant' => 'accent'],
            default => ['label' => 'PENDING', 'variant' => 'accent'],
        };
    }

    public function render()
    {
        return view('livewire.client.profile.profile')
            ->layout('layouts.client.client', ['title' => 'Profile — Star Unity', 'active' => 'profile']);
    }
}
