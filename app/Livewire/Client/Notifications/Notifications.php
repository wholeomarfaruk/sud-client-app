<?php

namespace App\Livewire\Client\Notifications;

use Livewire\Component;

class Notifications extends Component
{
    public array $notifications = [
        [
            'type' => 'due',
            'unread' => true,
            'title' => 'Installment #9 due soon',
            'body' => '৳4,50,000 due on 05 Jul for Apt B-7.',
            'time' => '2 hours ago',
        ],
        [
            'type' => 'paid',
            'unread' => false,
            'title' => 'Payment received',
            'body' => '৳35,000 service charge confirmed.',
            'time' => '2 Jun 2026',
        ],
        [
            'type' => 'offer',
            'unread' => false,
            'title' => 'New offer: Eid Booking Bonanza',
            'body' => 'Save up to ৳5 lakh on Greenview units.',
            'time' => '28 May 2026',
        ],
        [
            'type' => 'construction',
            'unread' => false,
            'title' => '9th floor slab casting complete',
            'body' => 'Star Unity Heights is ahead of schedule.',
            'time' => '21 May 2026',
        ],
    ];

    public function render()
    {
        return view('livewire.client.notifications.notifications')
            ->layout('layouts.client.client', ['title' => 'Notifications — Star Unity', 'active' => 'notifications']);
    }
}
