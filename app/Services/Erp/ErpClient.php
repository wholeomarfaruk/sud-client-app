<?php

namespace App\Services\Erp;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ErpClient
{
    public function login(string $login, string $password): array
    {
        return $this->request('post', '/login', [
            'login' => $login,
            'password' => $password,
        ]);
    }

    public function verifyOtp(string $verificationId, string $otp): array
    {
        return $this->request('post', '/verify-otp', [
            'otp' => $otp,
            'verification_id' => $verificationId,
        ]);
    }

    public function dashboard(string $accessToken): array
    {
        return $this->request('get', '/dashboard', [], $accessToken);
    }

    public function logout(string $accessToken): array
    {
        return $this->request('post', '/logout', [], $accessToken);
    }

    public function sidebar(string $accessToken): array
    {
        return $this->request('get', '/sidebar', [], $accessToken);
    }

    public function profile(string $accessToken): array
    {
        return $this->request('get', '/profile', [], $accessToken);
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl(rtrim(config('services.erp.base_url'), '/'))
            ->acceptJson()
            ->timeout((int) config('services.erp.timeout', 15));
    }

    protected function request(string $method, string $uri, array $data = [], ?string $accessToken = null): array
    {
        $client = $this->client();

        if ($accessToken) {
            $client = $client->withToken($accessToken);
        }

        try {
            $response = $method === 'get'
                ? $client->get($uri, $data)
                : $client->post($uri, $data);
        } catch (ConnectionException $e) {
            throw new ErpApiException('Unable to reach the server. Please try again.');
        }

        $payload = $response->json() ?? [];

        if ($response->failed() || ($payload['success'] ?? true) === false) {
            throw new ErpApiException(
                $payload['message'] ?? 'The server returned an unexpected error.',
                $response->status()
            );
        }

        return $payload;
    }
}
