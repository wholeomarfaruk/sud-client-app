<?php

namespace App\Livewire\Client\Auth;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class Otp extends Component
{
    public string $verificationId = '';

    public string $channel = 'email';

    public string $destination = '';

    public string $otpError = '';

    public function mount(): void
    {
        if (session()->has('client.access_token')) {
            $this->redirectRoute('dashboard');

            return;
        }

        if (! session()->has('client.verification_id')) {
            $this->redirectRoute('client.login');

            return;
        }

        $this->verificationId = session('client.verification_id');
        $this->channel = session('client.channel', 'email');
        $this->destination = session('client.destination', '');
    }

    public function verify(string $otp, ErpClient $erp): void
    {
        $this->otpError = '';

        try {
            $response = $erp->verifyOtp($this->verificationId, $otp);
        } catch (ErpApiException $e) {
            $this->otpError = $e->getMessage();

            return;
        }

        session()->forget(['client.verification_id', 'client.channel', 'client.destination']);

        session([
            'client.access_token' => $response['access_token'],
            'client.user' => $response['user'],
        ]);

        $this->redirectRoute('dashboard');
    }

    public function render()
    {
        return view('livewire.client.auth.otp')
            ->layout('layouts.website.website', ['title' => 'Verify Code — Star Unity']);
    }
}
