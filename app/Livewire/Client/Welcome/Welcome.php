<?php

namespace App\Livewire\Client\Welcome;

use Livewire\Component;

class Welcome extends Component
{
    public function render()
    {
        return view('livewire.client.welcome.welcome')
            ->layout('layouts.website.website', ['title' => 'Star Unity — Customer Portal']);
    }
}
