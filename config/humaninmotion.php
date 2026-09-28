<?php

return [
    'payments' => [
        'default' => env('PAYMENT_GATEWAY', 'mock'),
        'gateways' => [
            'mock' => [
                'label' => 'Demo — instant authorisation',
            ],
            'stripe' => [
                'label' => 'Card (Stripe)',
                'secret_key' => env('STRIPE_SECRET_KEY'),
                'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
                'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            ],
        ],
    ],
    'orders' => [
        'email' => env('ORDER_EMAIL', 'orders@humaninmotion.co.uk'),
    ],
];