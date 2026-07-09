<?php

namespace App\Livewire\Client\Notifications;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Carbon\Carbon;
use Livewire\Component;

class Notifications extends Component
{
    public array $notifications = [];

    public ?array $selected = null;

    public function mount(ErpClient $erp): void
    {
        $this->loadNotifications($erp);
    }

    protected function loadNotifications(ErpClient $erp): void
    {
        try {
            $data = $erp->notifications(session('client.access_token'));
        } catch (ErpApiException $e) {
            if ($e->statusCode() === 401) {
                session()->forget(['client.access_token', 'client.user']);
                session()->flash('status', 'Your session has expired. Please log in again.');
                $this->redirectRoute('client.login');

                return;
            }

            session()->flash('status', $e->getMessage());

            return;
        }

        $rows = $data['data']['data'] ?? [];

        $this->notifications = array_map(fn (array $row) => $this->mapRow($row), $rows);
    }

    protected function mapRow(array $row): array
    {
        $notification = $row['notification'] ?? [];
        $type = $notification['type'] ?? 'system';

        return [
            'id' => $row['id'],
            'type' => $type,
            'icon' => $this->iconFor($type),
            'unread' => ! ($row['is_read'] ?? false),
            'title' => $notification['title'] ?? '',
            'body' => $notification['body'] ?? '',
            'action_url' => $notification['action_url'] ?? null,
            'time' => isset($notification['sent_at']) ? Carbon::parse($notification['sent_at'])->diffForHumans() : '',
        ];
    }

    protected function iconFor(string $type): string
    {
        return match ($type) {
            'invoice_due' => 'due',
            'invoice_paid', 'payment_received' => 'paid',
            'offer' => 'offer',
            default => 'construction',
        };
    }

    public function view(int $notificationId, ErpClient $erp): void
    {
        $notification = collect($this->notifications)->firstWhere('id', $notificationId);

        if (! $notification) {
            return;
        }

        $this->selected = $notification;

        if ($notification['unread']) {
            $this->markRead($notificationId, $erp, refresh: false);
            $this->selected['unread'] = false;
        }
    }

    public function closeModal(): void
    {
        $this->selected = null;
    }

    public function markRead(int $notificationId, ErpClient $erp, bool $refresh = true): void
    {
        try {
            $erp->markNotificationRead(session('client.access_token'), $notificationId);
        } catch (ErpApiException $e) {
            session()->flash('status', $e->getMessage());

            return;
        }

        foreach ($this->notifications as &$n) {
            if ($n['id'] === $notificationId) {
                $n['unread'] = false;
            }
        }

        if ($refresh) {
            $this->dispatch('notifications-updated');
        }
    }

    public function markAllRead(ErpClient $erp): void
    {
        try {
            $erp->markAllNotificationsRead(session('client.access_token'));
        } catch (ErpApiException $e) {
            session()->flash('status', $e->getMessage());

            return;
        }

        foreach ($this->notifications as &$n) {
            $n['unread'] = false;
        }

        $this->dispatch('notifications-updated');
    }

    public function render()
    {
        return view('livewire.client.notifications.notifications')
            ->layout('layouts.client.client', ['title' => 'Notifications — Star Unity', 'active' => 'notifications']);
    }
}
