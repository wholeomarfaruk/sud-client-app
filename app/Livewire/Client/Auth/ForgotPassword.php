<?php

namespace App\Livewire\Client\Auth;

use Livewire\Component;

class ForgotPassword extends Component
{
    public function render()
    {
        return view('livewire.client.auth.forgot-password')
            ->layout('layouts.website.website', ['title' => 'Reset Password — Star Unity']);
    }
}
