<?php

namespace App\Livewire\Client\Dashboard;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public string $customerName = '';

    public array $stats = [
        'properties' => 0,
        'total_paid' => '৳ 0L',
        'outstanding' => '৳ 0L',
    ];

    public ?array $nextInstallment = null;

    public int $unreadNotifications = 0;

    public array $offers = [
        ['variant' => 'accent'],
        ['variant' => 'green'],
    ];

    public function mount(ErpClient $erp): void
    {
        try {
            $data = $erp->dashboard(session('client.access_token'));
        } catch (ErpApiException $e) {
            if ($e->statusCode() === 401) {
                session()->forget(['client.access_token', 'client.user']);
                session()->flash('status', 'Your session has expired. Please log in again.');
                $this->redirectRoute('client.login');

                return;
            }

            session()->flash('status', $e->getMessage());
            $this->redirectRoute('client.login');

            return;
        }

        $this->customerName = $data['user']['name'] ?? '';

        $summary = $data['summary'] ?? [];
        $this->stats = [
            'properties' => $summary['properties_count'] ?? 0,
            'total_paid' => bdt_lakh($summary['total_paid'] ?? 0),
            'outstanding' => bdt_lakh($summary['total_outstanding'] ?? 0),
        ];

        $this->nextInstallment = $this->mapNextPayment($data['next_payment'] ?? null);
        $this->unreadNotifications = $summary['unread_notifications'] ?? 0;
    }

    protected function mapNextPayment(?array $nextPayment): ?array
    {
        if (! $nextPayment) {
            return null;
        }

        $dueDate = Carbon::parse($nextPayment['due_date']);

        return [
            'due_in' => $dueDate->isPast()
                ? 'Overdue by ' . $dueDate->diffForHumans(null, true)
                : 'in ' . $dueDate->diffForHumans(null, true),
            'amount' => bdt_money($nextPayment['amount'] ?? 0),
            'meta' => trim(($nextPayment['property'] ?? '') . ' · ' . ($nextPayment['unit'] ?? '') . ' · Due ' . $dueDate->format('d M Y'), ' ·'),
        ];
    }

    public function render()
    {
        return view('livewire.client.dashboard.dashboard')
            ->layout('layouts.client.client', ['title' => 'Dashboard — Star Unity', 'active' => 'dashboard']);
    }
}
