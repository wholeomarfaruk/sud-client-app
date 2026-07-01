<?php

namespace App\Livewire\Client\Auth;

use Livewire\Component;

class Login extends Component
{
    public function render()
    {
        return view('livewire.client.auth.login')
            ->layout('layouts.website.website', ['title' => 'Log In — Star Unity']);
    }
}
