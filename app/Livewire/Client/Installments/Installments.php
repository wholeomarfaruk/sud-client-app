<?php

namespace App\Livewire\Client\Installments;

use Livewire\Component;

class Installments extends Component
{
    public array $properties = ['Heights', 'Greenview'];
    public string $selectedProperty = 'Heights';

    public array $summary = [
        'paid' => '8 / 13',
        'next_due' => '05 Jul',
        'remaining' => '৳ 23.5L',
    ];

    public array $schedule = [
        ['n' => 8, 'amount' => '৳ 4,50,000', 'state' => 'done', 'meta' => 'Paid · 10 Jun 2026'],
        ['n' => 9, 'amount' => '৳ 4,50,000', 'state' => 'due', 'meta' => 'Due in 6 days · 05 Jul 2026'],
        ['n' => 10, 'amount' => '৳ 4,50,000', 'state' => 'upcoming', 'meta' => 'Upcoming · 05 Aug 2026'],
        ['n' => 11, 'amount' => '৳ 4,50,000', 'state' => 'upcoming', 'meta' => 'Upcoming · 05 Sep 2026'],
    ];

    public function render()
    {
        return view('livewire.client.installments.installments')
            ->layout('layouts.client.client', ['title' => 'Installments — Star Unity', 'active' => 'installments']);
    }
}
