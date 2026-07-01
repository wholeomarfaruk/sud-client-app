<?php

namespace App\Livewire\Client\Profile;

use Livewire\Component;

class Profile extends Component
{
    public string $name = 'Md. Rafiqul Islam';
    public string $customerId = 'SUD-10472';

    public array $contact = [
        ['label' => 'Phone', 'value' => '+880 1712-345678'],
        ['label' => 'Email', 'value' => 'rafiqul.islam@gmail.com'],
        ['label' => 'Address', 'value' => 'Block C, Bashundhara R/A, Dhaka'],
    ];

    public string $nid = '1990 •••• •••• 4521';

    public function render()
    {
        return view('livewire.client.profile.profile')
            ->layout('layouts.client.client', ['title' => 'Profile — Star Unity', 'active' => 'profile']);
    }
}
