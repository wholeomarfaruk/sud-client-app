<?php

namespace App\Livewire\Client\Offers;

use Livewire\Component;

class Offers extends Component
{
    public array $offers = [
        [
            'variant' => 'accent',
            'tag' => 'SAVE ৳5 LAKH',
            'title' => 'Eid Booking Bonanza',
            'desc' => 'Book any Greenview unit before 15 Jul and save up to ৳5,00,000 on registration.',
            'valid' => 'Valid till 15 Jul 2026',
        ],
        [
            'variant' => 'green',
            'tag' => '0% INTEREST',
            'title' => 'Flexible 36-month plan',
            'desc' => 'Convert your balance into easy monthly installments with zero processing fee.',
            'valid' => 'Limited period',
        ],
    ];

    public function render()
    {
        return view('livewire.client.offers.offers')
            ->layout('layouts.client.client', ['title' => 'Offers — Star Unity', 'active' => 'offers']);
    }
}
