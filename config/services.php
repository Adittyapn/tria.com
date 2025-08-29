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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

        'rajaongkir' => [
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
        'shipping_cost_key' => env('RAJAONGKIR_SHIPPING_COST_KEY'),
        'shipping_delivery_key' => env('RAJAONGKIR_SHIPPING_DELIVERY_KEY'),
        'origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY_ID', 632), // Default: Bandung
        'timeout' => env('RAJAONGKIR_TIMEOUT', 30),
        'origin_district_id' => env('RAJAONGKIR_ORIGIN_DISTRICT_ID', 6536),
    ],

];
