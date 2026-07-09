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

    public function resendOtp(string $verificationId): array
    {
        return $this->request('post', '/resend-otp', [
            'verification_id' => $verificationId,
        ]);
    }

    /**
     * Send a password reset code to the account matching the given login
     * (phone or email). Always a generic success — the ERP never reveals
     * whether an account exists. Server-side cooldown is 60s between sends.
     */
    public function forgotPassword(string $login): array
    {
        return $this->request('post', '/forgot-password', [
            'login' => $login,
        ]);
    }

    /**
     * Verify the reset code and set the new password in one step (the ERP
     * has no separate "verify code" call for this flow). Code expires in
     * 15 minutes; 5 wrong attempts burns it.
     */
    public function resetPassword(string $login, string $code, string $password, string $passwordConfirmation): array
    {
        return $this->request('post', '/reset-password', [
            'login' => $login,
            'code' => $code,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
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

    /**
     * My Properties v2 — units list (screen 16). One row per purchased/rented
     * unit (a sale invoice can cover multiple units).
     * Payload: ['success' => true, 'data' => [ [
     *   id (the PropertySaleUnit id — pass this to myPropertiesUnitWiseDetail()),
     *   sale_id, sale_number, sale_type: sale|rent, sale_type_label, status,
     *   payment_status: pending|partial|paid, sale_date, contract_date,
     *   property: [id, name, code, address, type, total_area, land_size],
     *   unit: [id, code, type, status, status_label, area, price, bedrooms,
     *          bathrooms, balcony, facing, floor],
     *   pricing: [sale_amount, discount_amount, tax_amount, net_amount,
     *             service_charge, utility_charge, down_payment_percentage],
     *   cover_image_url, images,
     * ], ... ] ]
     * Note: no single field carries the design's Booked/On Installment/
     * Purchased/Handover/Rented pill — see MyProperties::deriveUnitStatus().
     */
    public function myPropertiesUnitWise(string $accessToken): array
    {
        return $this->request('get', '/my-properties-unit-wise', [], $accessToken);
    }

    /**
     * My Properties v2 — Unit & Purchase Details (screen 17), keyed by the
     * parent sale id + the PropertySaleUnit id (a sale can cover multiple units).
     * Payload: same as myPropertiesUnitWise()'s row, plus:
     *   documents: [ [id, name, caption, extension, url] ],
     *   paid_amount, due_amount, notes, is_handed_over, handover_date, created_at.
     */
    public function myPropertiesUnitWiseDetail(string $accessToken, string $sale, string $saleUnit): array
    {
        return $this->request('get', "/my-properties-unit-wise/{$sale}/units/{$saleUnit}", [], $accessToken);
    }

    /**
     * Invoices — sales list (Invoices page). One row per sale invoice (a sale
     * can bundle multiple units under one combined purchase).
     * Payload: ['success' => true, 'data' => [ [
     *   id, sale_number, sale_type: sale|rent, sale_type_label, status,
     *   payment_status: pending|partial|paid, sale_date, contract_date,
     *   property: [id, name, code, address, type, total_area, land_size],
     *   unit: [...], units: [ [id, code, type, status, status_label, area, price, ...] ],
     *   sale_amount, net_amount, paid_amount, due_amount, cover_image_url,
     *   schedule_summary: [total, paid, overdue, unpaid],
     * ], ... ] ]
     */
    public function properties(string $accessToken): array
    {
        return $this->request('get', '/properties', [], $accessToken);
    }

    /**
     * Invoices — full sale detail (Invoice Detail page), keyed by PropertySale id.
     * Payload: ['success' => true, 'data' => [
     *   id, sale_number, sale_type: sale|rent, sale_type_label, status,
     *   payment_status: pending|partial|paid, sale_date, contract_date,
     *   property: [id, name, code, address, type, total_area, land_size],
     *   unit: [...], units: [ [id, code, type, status, status_label, area,
     *          price, bedrooms, bathrooms, balcony, facing, floor] ],
     *   sale_amount, net_amount, paid_amount, due_amount, cover_image_url,
     *   discount_amount, tax_amount, down_payment_amount, down_payment_percentage,
     *   payment_terms, schedule: [is_scheduled, count, amount, name, type, day,
     *   start_date, status], rent: [...] | null, sales_representative, notes,
     *   features, terms_conditions, is_handed_over, handover_date, created_at,
     * ] ]
     * Note: `units` here has no per-unit property override — every unit in a
     * sale is attributed to the sale's single `property` at this endpoint.
     */
    public function propertySale(string $accessToken, string $sale): array
    {
        return $this->request('get', '/properties/'.$sale, [], $accessToken);
    }

    /** Invoices — documents attached to the sold property. Payload: ['data' => [ [id, name, caption, extension, url] ] ] */
    public function propertySaleDocuments(string $accessToken, string $sale): array
    {
        return $this->request('get', '/properties/'.$sale.'/documents', [], $accessToken);
    }

    /**
     * Invoices — full payment schedule with transactions, ordered down_payment →
     * security_deposit → installment → monthly_rent → extra_charge → manual_charge.
     * Payload: ['data' => [ [id, payment_category, label, sequence_no, due_date,
     *   amount, paid_amount, due_amount, status: pending|partial|paid,
     *   display_status: pending|partial|paid|overdue, is_overdue, remarks,
     *   transactions: [ [id, amount, method, reference_no, payer_name, phone,
     *   notes, datetime, attachments] ],
     * ], ... ] ]
     */
    public function propertySalePaymentSchedules(string $accessToken, string $sale): array
    {
        return $this->request('get', '/properties/'.$sale.'/payment-schedules', [], $accessToken);
    }

    /**
     * Payment History — every schedule the customer has paid against (partial
     * or fully paid), across all properties, grouped by month.
     * Payload: ['success' => true, 'data' => [
     *   summary: [total_paid, transaction_count, since],
     *   properties: [ [id, name, code] ],
     *   groups: [ [month, month_label, items: [ [
     *     id, sale_id, sale_number, payment_category, label, sequence_no,
     *     amount, paid_amount, due_amount, status, display_status,
     *     due_date, paid_date, method, property: [id, name, code] | null,
     *     transactions: [ [id, amount, method, reference_no, payer_name,
     *     phone, notes, datetime, attachments] ],
     *   ] ] ] ],
     * ] ]
     */
    public function paymentHistory(string $accessToken): array
    {
        return $this->request('get', '/payment-history', [], $accessToken);
    }

    /**
     * Notifications — paginated list (Notifications page).
     * Payload: ['success' => true, 'data' => [ 'data' => [ [
     *   id, is_read, read_at,
     *   notification: [id, type, title, body, badge, action_url, sent_at],
     * ], ... ], 'current_page', 'last_page', ... ] ]
     */
    public function notifications(string $accessToken, int $page = 1): array
    {
        return $this->request('get', '/notifications', ['page' => $page], $accessToken);
    }

    public function unreadNotificationCount(string $accessToken): array
    {
        return $this->request('get', '/notifications/unread-count', [], $accessToken);
    }

    public function markNotificationRead(string $accessToken, int $notificationId): array
    {
        return $this->request('post', "/notifications/{$notificationId}/read", [], $accessToken);
    }

    public function markAllNotificationsRead(string $accessToken): array
    {
        return $this->request('post', '/notifications/mark-all-read', [], $accessToken);
    }

    /** Registers/updates a browser push subscription. Server-side dedupes by endpoint. */
    public function storePushSubscription(string $accessToken, string $endpoint, string $p256dh, string $auth): array
    {
        return $this->request('post', '/push-subscriptions', [
            'endpoint' => $endpoint,
            'keys' => [
                'p256dh' => $p256dh,
                'auth' => $auth,
            ],
        ], $accessToken);
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
