<?php

namespace App\Livewire\Client\Properties;

use Livewire\Component;

class PropertyDetail extends Component
{
    public array $propertyData;

    public array $documents = [
        ['name' => 'Allotment Letter', 'meta' => 'PDF · 240 KB', 'type' => 'pdf'],
        ['name' => 'Sale Agreement (Deed)', 'meta' => 'PDF · 1.2 MB', 'type' => 'pdf'],
        ['name' => 'Latest Payment Receipt', 'meta' => 'PDF · 96 KB', 'type' => 'receipt'],
        ['name' => 'Previous Payment Receipt', 'meta' => 'PDF · 88 KB', 'type' => 'receipt'],
    ];

    protected array $properties = [
        1 => [
            'id' => 1,
            'name' => 'Star Unity Heights',
            'address' => 'Apt B-7 · Block B · Bashundhara R/A, Dhaka',
            'size' => '1,450 sqft',
            'floor' => '7th',
            'facing' => 'South',
        ],
        2 => [
            'id' => 2,
            'name' => 'Unity Greenview',
            'address' => 'Apt A-3 · Block A · Mirpur DOHS, Dhaka',
            'size' => '1,180 sqft',
            'floor' => '3rd',
            'facing' => 'East',
        ],
    ];

    public function mount($property = 1): void
    {
        $this->propertyData = $this->properties[(int) $property] ?? $this->properties[1];
    }

    public function render()
    {
        return view('livewire.client.properties.property-detail')
            ->layout('layouts.client.client', ['title' => $this->propertyData['name'].' — Star Unity', 'active' => 'my-properties']);
    }
}
