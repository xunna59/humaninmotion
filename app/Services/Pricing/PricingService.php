<?php

namespace App\Services\Pricing;

class PricingService
{
    public const CURRENCY = 'GBP';

    public function currency(): string
    {
        return self::CURRENCY;
    }

    public function format(float|int $amount): string
    {
        return \App\Support\Money::formatFloat($amount, self::CURRENCY);
    }

    public function subtotal(\App\Models\Cart $cart): float
    {
        return $cart->items->sum(fn ($item) => (float) $item->unit_price * $item->quantity);
    }

    public function quantity(\App\Models\Cart $cart): int
    {
        return $cart->items->sum('quantity');
    }

    public function discountFor(\App\Models\Cart $cart, mixed $discount): float
    {
        return (float) ($discount['amount'] ?? 0);
    }

    public function shippingFor(\App\Models\Cart $cart, string $method): float
    {
        $service = app(\App\Services\Shipping\ShippingService::class);

        return $service->rateFor($method, $cart);
    }
}