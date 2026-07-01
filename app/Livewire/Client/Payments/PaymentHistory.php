<?php

namespace App\Livewire\Client\Payments;

use Livewire\Component;

class PaymentHistory extends Component
{
    public string $totalPaid = '৳ 38,50,000';
    public string $summaryMeta = '14 transactions · since Mar 2024';

    public array $months = [
        [
            'label' => 'June 2026',
            'rows' => [
                ['title' => 'Installment #8', 'meta' => '10 Jun · bKash · Heights', 'amount' => '৳ 4,50,000', 'property' => 'heights'],
                ['title' => 'Utility & service charge', 'meta' => '02 Jun · Bank · Greenview', 'amount' => '৳ 35,000', 'property' => 'greenview'],
            ],
        ],
        [
            'label' => 'May 2026',
            'rows' => [
                ['title' => 'Installment #7', 'meta' => '11 May · bKash · Heights', 'amount' => '৳ 4,50,000', 'property' => 'heights'],
            ],
        ],
    ];

    public function render()
    {
        return view('livewire.client.payments.payment-history')
            ->layout('layouts.client.client', ['title' => 'Payment History — Star Unity', 'active' => 'payment-history']);
    }
}
