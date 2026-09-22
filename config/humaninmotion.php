<?php

return [
    'payments' => [
        'default' => env('PAYMENT_GATEWAY', 'mock'),
        'gateways' => [
            'mock' => [
                'label' => 'Demo — instant authorisation',
            ],
        ],
    ],
    'orders' => [
        'email' => env('ORDER_EMAIL', 'orders@humaninmotion.co.uk'),
    ],
];