<?php

namespace App\Livewire\Client\Noticeboard;

use Livewire\Component;

class Noticeboard extends Component
{
    public array $notices = [
        [
            'pinned' => true,
            'category' => null,
            'title' => 'Eid-ul-Adha office & collection schedule',
            'body' => 'Head office will remain closed 6–9 July. Online payments stay open via the app.',
            'date' => '27 Jun 2026',
        ],
        [
            'pinned' => false,
            'category' => 'CONSTRUCTION',
            'variant' => 'green',
            'title' => 'Star Unity Heights — slab casting of 9th floor done',
            'body' => 'Project is 2 weeks ahead of schedule. Handover expected Q2 2027.',
            'date' => '21 Jun 2026',
        ],
        [
            'pinned' => false,
            'category' => 'PAYMENT',
            'variant' => 'gold',
            'title' => 'New bank account for installment deposits',
            'body' => 'Please use the updated A/C details shown on your installment page from July.',
            'date' => '14 Jun 2026',
        ],
    ];

    public function render()
    {
        return view('livewire.client.noticeboard.noticeboard')
            ->layout('layouts.client.client', ['title' => 'Noticeboard — Star Unity', 'active' => 'noticeboard']);
    }
}
