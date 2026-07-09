<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'erp' => [
        'base_url' => env('ERP_API_BASE_URL', 'http://127.0.0.1:8000/api/client'),
        'timeout' => env('ERP_API_TIMEOUT', 15),
    ],

    'webpush' => [
        'vapid_public_key' => env('VAPID_PUBLIC_KEY'),
    ],

    'website' => [
        'base_url' => env('WEBSITE_API_BASE_URL', 'http://127.0.0.1:8200/api'),
        'api_key' => env('WEBSITE_API_KEY'),
        'timeout' => env('WEBSITE_API_TIMEOUT', 15),
    ],

];
