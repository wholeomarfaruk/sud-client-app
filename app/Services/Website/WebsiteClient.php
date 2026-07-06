<?php

namespace App\Services\Website;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Client for the marketing website's content API (starunitydevelopment.com —
 * Offers & News/Blogs). Auth is a static X-API-KEY header, not a per-user
 * bearer token like App\Services\Erp\ErpClient.
 */
class WebsiteClient
{
    /** Paginated payload: Laravel's default paginator shape — [data, current_page, last_page, next_page_url, ...]. */
    public function blogs(int $page = 1, int $perPage = 20): array
    {
        return $this->request('/blogs', ['page' => $page, 'per_page' => $perPage]);
    }

    /** Single blog: [id, title, description, featured_image, is_featured, meta_title, meta_description, meta_image, created_at, post_link]. */
    public function blog(int $id): array
    {
        return $this->request('/blogs/'.$id);
    }

    /** Paginated payload, same shape as blogs(). */
    public function offers(int $page = 1, int $perPage = 20): array
    {
        return $this->request('/offers', ['page' => $page, 'per_page' => $perPage]);
    }

    /** Single offer — same fields as a blog item. */
    public function offer(int $id): array
    {
        return $this->request('/offers/'.$id);
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl(rtrim(config('services.website.base_url'), '/'))
            ->withHeaders(['X-API-KEY' => config('services.website.api_key')])
            ->acceptJson()
            ->timeout((int) config('services.website.timeout', 15));
    }

    protected function request(string $uri, array $query = []): array
    {
        try {
            $response = $this->client()->get($uri, $query);
        } catch (ConnectionException $e) {
            throw new WebsiteApiException('Unable to reach the website. Please try again.');
        }

        if ($response->failed()) {
            throw new WebsiteApiException(
                $response->json('message') ?? 'The website returned an unexpected error.',
                $response->status()
            );
        }

        return $response->json() ?? [];
    }
}
