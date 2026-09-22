<?php

namespace App\Services\Shipping;

use App\Models\Cart;

class ShippingService
{
    public function methods(): array
    {
        return [
            [
                'code' => 'uk_standard',
                'name' => 'UK Standard',
                'price' => 3.95,
                'estimate' => '2–4 working days',
                'zones' => ['United Kingdom'],
                'free_above' => (float) app(\App\Services\SettingsService::class)->get('free_shipping_threshold', 100),
            ],
            [
                'code' => 'uk_express',
                'name' => 'UK Express',
                'price' => 6.95,
                'estimate' => '1–2 working days',
                'zones' => ['United Kingdom'],
                'free_above' => null,
            ],
            [
                'code' => 'europe',
                'name' => 'Europe Standard',
                'price' => 12.00,
                'estimate' => '5–9 working days',
                'zones' => ['Ireland', 'France', 'Germany', 'Spain', 'Italy', 'Netherlands', 'Belgium', 'Austria', 'Portugal'],
                'free_above' => 200.0,
            ],
            [
                'code' => 'intl',
                'name' => 'International',
                'price' => 18.00,
                'estimate' => '7–14 working days',
                'zones' => ['United States', 'Canada', 'Australia', 'United Arab Emirates'],
                'free_above' => 250.0,
            ],
        ];
    }

    public function method(string $code): ?array
    {
        return collect($this->methods())->firstWhere('code', $code);
    }

    public function rateFor(string $code, Cart $cart): float
    {
        $method = $this->method($code);
        if (! $method) {
            return 0;
        }

        $subtotal = $cart->subtotal();

        if ($method['free_above'] !== null && $subtotal >= $method['free_above']) {
            return 0;
        }

        return (float) $method['price'];
    }

    public function estimateFor(string $code): string
    {
        return $this->method($code)['estimate'] ?? '';
    }
}