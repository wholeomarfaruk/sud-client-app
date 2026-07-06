<?php

namespace App\Livewire\Client\News;

use App\Services\Website\WebsiteApiException;
use App\Services\Website\WebsiteClient;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class News extends Component
{
    public ?array $featured = null;

    public array $articles = [];

    public int $page = 1;

    public bool $hasMore = false;

    public string $loadError = '';

    public function mount(WebsiteClient $website): void
    {
        try {
            $data = $website->blogs(1);
        } catch (WebsiteApiException $e) {
            $this->loadError = $e->getMessage();

            return;
        }

        $blogs = array_map(fn (array $blog) => $this->mapBlog($blog), $data['data'] ?? []);

        $featuredIndex = 0;
        foreach ($blogs as $i => $blog) {
            if ($blog['is_featured']) {
                $featuredIndex = $i;
                break;
            }
        }

        $this->featured = $blogs[$featuredIndex] ?? null;
        unset($blogs[$featuredIndex]);
        $this->articles = array_values($blogs);

        $this->hasMore = ! empty($data['next_page_url']);
    }

    public function loadMore(WebsiteClient $website): void
    {
        $this->loadError = '';

        try {
            $data = $website->blogs($this->page + 1);
        } catch (WebsiteApiException $e) {
            $this->loadError = $e->getMessage();

            return;
        }

        $this->page++;
        $this->articles = array_merge($this->articles, array_map(fn (array $blog) => $this->mapBlog($blog), $data['data'] ?? []));
        $this->hasMore = ! empty($data['next_page_url']);
    }

    protected function mapBlog(array $blog): array
    {
        $plainText = trim(strip_tags($blog['description'] ?? ''));

        return [
            'id' => $blog['id'],
            'title' => $blog['title'] ?? '',
            'excerpt' => Str::limit($plainText, 120),
            'image' => $blog['featured_image'] ?? null,
            'is_featured' => (bool) ($blog['is_featured'] ?? false),
            'date' => ! empty($blog['created_at']) ? Carbon::parse($blog['created_at'])->format('d M Y') : '',
            'read_time' => $this->estimateReadTime($plainText),
        ];
    }

    protected function estimateReadTime(string $plainText): string
    {
        $words = str_word_count($plainText);
        $minutes = max(1, (int) ceil($words / 200));

        return $minutes.' min read';
    }

    public function render()
    {
        return view('livewire.client.news.news')
            ->layout('layouts.client.client', ['title' => 'News & Blogs — Star Unity', 'active' => 'news']);
    }
}
