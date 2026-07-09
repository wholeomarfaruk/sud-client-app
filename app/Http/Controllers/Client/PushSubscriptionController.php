<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request, ErpClient $erp): JsonResponse
    {
        $token = session('client.access_token');

        if (! $token) {
            return response()->json(['success' => false], 401);
        }

        $validated = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        try {
            $erp->storePushSubscription($token, $validated['endpoint'], $validated['keys']['p256dh'], $validated['keys']['auth']);
        } catch (ErpApiException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->statusCode() ?: 500);
        }

        return response()->json(['success' => true]);
    }
}
