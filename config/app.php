<?php

use App\Support\Env;

return [
    'name' => Env::get('APP_NAME', 'Welkar'),
    'env' => Env::get('APP_ENV', 'local'),
    'debug' => Env::get('APP_DEBUG', true),
    'url' => Env::get('APP_URL', 'http://localhost:8000'),
    'timezone' => Env::get('APP_TIMEZONE', 'Asia/Kolkata'),
    'currency' => Env::get('DEFAULT_CURRENCY', 'INR'),
    'currency_symbol' => Env::get('CURRENCY_SYMBOL', '₹'),
    'locale' => Env::get('DEFAULT_LOCALE', 'en'),
    'fallback_locale' => Env::get('FALLBACK_LOCALE', 'en'),

    'business' => [
        'legal_name' => Env::get('BUSINESS_LEGAL_NAME', 'Welkar Timepieces Private Limited'),
        'trade_name' => Env::get('BUSINESS_TRADE_NAME', 'Welkar'),
        'email' => Env::get('BUSINESS_EMAIL', 'welkarstore@gmail.com'),
        'phone' => Env::get('BUSINESS_PHONE', '+91 98765 43210'),
        'gstin' => Env::get('BUSINESS_GSTIN', ''),
        'state' => Env::get('BUSINESS_STATE', 'Maharashtra'),
        'state_code' => Env::get('BUSINESS_STATE_CODE', '27'),
        'default_gst_rate' => (float)Env::get('DEFAULT_GST_RATE', 18.00),
    ],

    'shipping' => [
        'default_fee' => (float)Env::get('DEFAULT_SHIPPING_FEE', 60.00),
        'free_threshold' => (float)Env::get('FREE_SHIPPING_THRESHOLD', 1499.00),
        'cod_enabled' => (bool)Env::get('COD_ENABLED', true),
        'cod_max_amount' => (float)Env::get('COD_MAX_AMOUNT', 5000.00),
    ],

    'razorpay' => [
        'mode' => Env::get('RAZORPAY_MODE', 'test'),
        'key_id' => Env::get('RAZORPAY_KEY_ID', 'rzp_test_placeholder'),
        'key_secret' => Env::get('RAZORPAY_KEY_SECRET', 'placeholder_secret'),
        'webhook_secret' => Env::get('RAZORPAY_WEBHOOK_SECRET', ''),
    ],

    'mail' => [
        'driver' => Env::get('MAIL_DRIVER', 'smtp'),
        'host' => Env::get('MAIL_HOST', 'smtp.gmail.com'),
        'port' => (int)Env::get('MAIL_PORT', 587),
        'username' => Env::get('MAIL_USERNAME', ''),
        'password' => Env::get('MAIL_PASSWORD', ''),
        'encryption' => Env::get('MAIL_ENCRYPTION', 'tls'),
        'from_address' => Env::get('MAIL_FROM_ADDRESS', 'welkarstore@gmail.com'),
        'from_name' => Env::get('MAIL_FROM_NAME', 'Welkar Watches'),
    ],

    'meta' => [
        'pixel_id' => Env::get('META_PIXEL_ID', ''),
        'access_token' => Env::get('META_CONVERSIONS_API_TOKEN', ''),
        'test_event_code' => Env::get('META_TEST_EVENT_CODE', ''),
        'enabled' => !empty(Env::get('META_PIXEL_ID', '')),
    ]
];

