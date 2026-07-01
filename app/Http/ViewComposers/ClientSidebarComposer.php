<?php

namespace App\Http\ViewComposers;

use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ClientSidebarComposer
{
    public function __construct(protected ErpClient $erp)
    {
    }

    public function compose(View $view): void
    {
        $view->with('sidebar', $this->resolve());
    }

    protected function resolve(): ?array
    {
        $token = session('client.access_token');

        if (! $token) {
            return null;
        }

        return Cache::remember(
            'client.sidebar.'.md5($token),
            now()->addMinutes(5),
            function () use ($token) {
                try {
                    return $this->erp->sidebar($token);
                } catch (ErpApiException) {
                    return null;
                }
            }
        );
    }
}
