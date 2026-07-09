<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Erp\ErpApiException;
use App\Services\Erp\ErpClient;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function read(int $notification, ErpClient $erp): JsonResponse
    {
        $token = session('client.access_token');

        if (! $token) {
            return response()->json(['success' => false], 401);
        }

        try {
            $erp->markNotificationRead($token, $notification);
        } catch (ErpApiException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], $e->statusCode() ?: 500);
        }

        return response()->json(['success' => true]);
    }
}
