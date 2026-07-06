<?php

namespace App\Livewire\Client\Offers;

use App\Services\Website\WebsiteApiException;
use App\Services\Website\WebsiteClient;
use Carbon\Carbon;
use Livewire\Component;

class OfferDetail extends Component
{
    public array $offer = [];

    public function mount(WebsiteClient $website, int $offerId): void
    {
        try {
            $data = $website->offer($offerId);
        } catch (WebsiteApiException $e) {
            abort($e->statusCode() === 404 ? 404 : 500, $e->getMessage());

            return;
        }

        $this->offer = [
            'title' => $data['title'] ?? '',
            'body' => $data['description'] ?? '',
            'image' => $data['featured_image'] ?? null,
            'date' => ! empty($data['created_at']) ? Carbon::parse($data['created_at'])->format('d M Y') : '',
            'is_featured' => (bool) ($data['is_featured'] ?? false),
        ];
    }

    public function render()
    {
        return view('livewire.client.offers.offer-detail')
            ->layout('layouts.client.client', ['title' => ($this->offer['title'] ?? 'Offer').' — Star Unity', 'active' => 'offers']);
    }
}
