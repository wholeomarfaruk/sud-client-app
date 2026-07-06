<?php

namespace App\Livewire\Client\Offers;

use App\Services\Website\WebsiteApiException;
use App\Services\Website\WebsiteClient;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class Offers extends Component
{
    public array $offers = [];

    public int $page = 1;

    public bool $hasMore = false;

    public string $loadError = '';

    public function mount(WebsiteClient $website): void
    {
        try {
            $data = $website->offers(1);
        } catch (WebsiteApiException $e) {
            $this->loadError = $e->getMessage();

            return;
        }

        $this->offers = array_map(
            fn (array $offer, int $i) => $this->mapOffer($offer, $i),
            $data['data'] ?? [],
            array_keys($data['data'] ?? [])
        );
        $this->hasMore = ! empty($data['next_page_url']);
    }

    public function loadMore(WebsiteClient $website): void
    {
        $this->loadError = '';

        try {
            $data = $website->offers($this->page + 1);
        } catch (WebsiteApiException $e) {
            $this->loadError = $e->getMessage();

            return;
        }

        $this->page++;
        $offset = count($this->offers);
        $this->offers = array_merge($this->offers, array_map(
            fn (array $offer, int $i) => $this->mapOffer($offer, $offset + $i),
            $data['data'] ?? [],
            array_keys($data['data'] ?? [])
        ));
        $this->hasMore = ! empty($data['next_page_url']);
    }

    protected function mapOffer(array $offer, int $index): array
    {
        return [
            'id' => $offer['id'],
            'title' => $offer['title'] ?? '',
            'desc' => Str::limit(trim(strip_tags($offer['description'] ?? '')), 140),
            'image' => $offer['featured_image'] ?? null,
            'is_featured' => (bool) ($offer['is_featured'] ?? false),
            'date' => ! empty($offer['created_at']) ? Carbon::parse($offer['created_at'])->format('d M Y') : '',
            'variant' => $index % 2 === 0 ? 'accent' : 'green',
        ];
    }

    public function render()
    {
        return view('livewire.client.offers.offers')
            ->layout('layouts.client.client', ['title' => 'Offers — Star Unity', 'active' => 'offers']);
    }
}
