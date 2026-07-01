<?php

namespace App\Livewire\Client\Auth;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class Login extends Component
{
    public string $login = '';

    public string $password = '';

    public function mount(): void
    {
        if (session()->has('client.access_token')) {
            $this->redirectRoute('dashboard');
        }
    }

    public function rules()
    {
        return [
            'login' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function submit(ErpClient $erp): void
    {
        $this->validate();

        try {
            $response = $erp->login($this->login, $this->password);
        } catch (ErpApiException $e) {
            $this->addError('login', $e->getMessage());

            return;
        }

        session([
            'client.verification_id' => $response['verification_id'] ?? null,
            'client.channel' => $response['channel'] ?? 'email',
            'client.destination' => $response['destination'] ?? '',
        ]);

        $this->redirectRoute('client.otp');
    }

    public function render()
    {
        return view('livewire.client.auth.login')
            ->layout('layouts.website.website', ['title' => 'Log In — Star Unity']);
    }
}
