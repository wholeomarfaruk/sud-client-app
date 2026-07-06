<?php

namespace App\Livewire\Client\Auth;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $login = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $otpError = '';

    public function mount(): void
    {
        if (! session()->has('client.reset_login')) {
            $this->redirectRoute('client.forgot-password');

            return;
        }

        $this->login = session('client.reset_login');
    }

    /** Bound to <x-client.otp-input>'s "Verify & continue" button — verifies the code and sets the new password in one call. */
    public function verify(string $otp, ErpClient $erp): void
    {
        $this->otpError = '';

        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $erp->resetPassword($this->login, $otp, $this->password, $this->password_confirmation);
        } catch (ErpApiException $e) {
            $this->otpError = $e->getMessage();

            return;
        }

        session()->forget('client.reset_login');
        session()->flash('status', 'Your password has been reset. Please log in again.');

        $this->redirectRoute('client.login');
    }

    public function resend(ErpClient $erp): void
    {
        $this->otpError = '';

        try {
            $erp->forgotPassword($this->login);
        } catch (ErpApiException $e) {
            $this->otpError = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.client.auth.reset-password')
            ->layout('layouts.website.website', ['title' => 'Set New Password — Star Unity']);
    }
}
