<?php

namespace App\Livewire\Client\News;

use App\Services\Website\WebsiteApiException;
use App\Services\Website\WebsiteClient;
use Carbon\Carbon;
use Livewire\Component;

class NewsDetail extends Component
{
    public array $article = [];

    public function mount(WebsiteClient $website, int $news): void
    {
        try {
            $blog = $website->blog($news);
        } catch (WebsiteApiException $e) {
            abort($e->statusCode() === 404 ? 404 : 500, $e->getMessage());

            return;
        }

        $this->article = [
            'title' => $blog['title'] ?? '',
            'body' => $blog['description'] ?? '',
            'image' => $blog['featured_image'] ?? null,
            'date' => ! empty($blog['created_at']) ? Carbon::parse($blog['created_at'])->format('d M Y') : '',
        ];
    }

    public function render()
    {
        return view('livewire.client.news.news-detail')
            ->layout('layouts.client.client', ['title' => ($this->article['title'] ?? 'Article').' — Star Unity', 'active' => 'news']);
    }
}
