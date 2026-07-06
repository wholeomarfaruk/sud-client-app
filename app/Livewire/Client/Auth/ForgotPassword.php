<?php

namespace App\Livewire\Client\Auth;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $login = '';

    public function rules(): array
    {
        return [
            'login' => 'required|string',
        ];
    }

    /** Clear a stale "field is required"/API error the moment the user edits the field again. */
    public function updatedLogin(): void
    {
        $this->resetErrorBag('login');
    }

    public function submit(ErpClient $erp): void
    {
        $this->validate();

        try {
            $erp->forgotPassword($this->login);
        } catch (ErpApiException $e) {
            $this->addError('login', $e->getMessage());

            return;
        }

        session(['client.reset_login' => $this->login]);

        $this->redirectRoute('client.reset-password');
    }

    public function render()
    {
        return view('livewire.client.auth.forgot-password')
            ->layout('layouts.website.website', ['title' => 'Reset Password — Star Unity']);
    }
}
