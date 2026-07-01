<?php

namespace App\Livewire\Client\News;

use Livewire\Component;

class News extends Component
{
    public array $featured = [
        'title' => "Why Bashundhara R/A is Dhaka's smartest 2026 investment",
        'meta' => 'Star Unity Insights · 24 Jun 2026 · 5 min read',
    ];

    public array $articles = [
        [
            'thumb' => 'gold',
            'title' => '5 documents to keep ready before flat handover',
            'meta' => '18 Jun 2026 · 3 min read',
        ],
        [
            'thumb' => 'green',
            'title' => 'Home loan vs developer installment — which fits you?',
            'meta' => '09 Jun 2026 · 6 min read',
        ],
    ];

    public function render()
    {
        return view('livewire.client.news.news')
            ->layout('layouts.client.client', ['title' => 'News & Blogs — Star Unity', 'active' => 'news']);
    }
}
