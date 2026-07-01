<?php

namespace App\Livewire\Client\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public string $customerName = 'Md. Rafiqul Islam';

    public array $stats = [
        'properties' => 2,
        'total_paid' => '৳ 38.5L',
        'outstanding' => '৳ 23.5L',
    ];

    public array $nextInstallment = [
        'due_in' => 'in 6 days',
        'amount' => '৳ 4,50,000',
        'meta' => 'Star Unity Heights · Apt B-7 · Due 05 Jul 2026',
    ];

    public array $offers = [
        ['variant' => 'accent'],
        ['variant' => 'green'],
    ];

    public function render()
    {
        return view('livewire.client.dashboard.dashboard')
            ->layout('layouts.client.client', ['title' => 'Dashboard — Star Unity', 'active' => 'dashboard']);
    }
}
