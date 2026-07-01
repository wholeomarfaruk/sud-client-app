<?php

namespace App\Livewire\Client\Properties;

use Livewire\Component;

class MyProperties extends Component
{
    public array $properties = [
        [
            'id' => 1,
            'name' => 'Star Unity Heights',
            'meta' => 'Apt B-7 · 1,450 sqft · Bashundhara R/A',
            'status' => 'ON SCHEDULE',
            'variant' => 'green',
            'progress' => 62,
            'total_price' => '৳ 62,00,000',
            'outstanding' => '৳ 23,50,000',
        ],
        [
            'id' => 2,
            'name' => 'Unity Greenview',
            'meta' => 'Apt A-3 · 1,180 sqft · Mirpur DOHS',
            'status' => 'HANDOVER SOON',
            'variant' => 'gold',
            'progress' => 88,
            'total_price' => null,
            'outstanding' => null,
        ],
    ];

    public function render()
    {
        return view('livewire.client.properties.my-properties')
            ->layout('layouts.client.client', ['title' => 'My Properties — Star Unity', 'active' => 'my-properties']);
    }
}
