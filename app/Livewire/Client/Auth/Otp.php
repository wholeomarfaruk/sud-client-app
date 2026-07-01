<?php

namespace App\Livewire\Client\Auth;

use Livewire\Component;

class Otp extends Component
{
    public function render()
    {
        return view('livewire.client.auth.otp')
            ->layout('layouts.website.website', ['title' => 'Verify Code — Star Unity']);
    }
}
