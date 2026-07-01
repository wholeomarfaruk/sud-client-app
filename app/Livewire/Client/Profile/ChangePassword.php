<?php

namespace App\Livewire\Client\Profile;

use Livewire\Component;

class ChangePassword extends Component
{
    public function render()
    {
        return view('livewire.client.profile.change-password')
            ->layout('layouts.client.client', ['title' => 'Change Password — Star Unity', 'active' => 'profile']);
    }
}
