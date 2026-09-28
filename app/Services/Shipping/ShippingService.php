<?php

namespace App\Services\Shipping;

use App\Models\Cart;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Builder;

class ShippingService
{
    public function methods(): array
    {
        return $this->query()
            ->get()
            ->map(fn (ShippingMethod $method) => $this->toArray($method))
            ->values()
            ->toArray();
    }

    public function codes(): array
    {
        return $this->query()->pluck('code')->all();
    }

    public function method(string $code): ?array
    {
        $row = ShippingMethod::query()->where('code', $code)->first();

        return $row ? $this->toArray($row) : null;
    }

    public function defaultCode(): string
    {
        return $this->query()->value('code') ?? 'uk_standard';
    }

    public function rateFor(string $code, Cart $cart): float
    {
        $row = ShippingMethod::query()->where('code', $code)->first();

        if (! $row) {
            return 0;
        }

        $subtotal = $cart->subtotal();

        if ($row->free_above !== null && $subtotal >= $row->free_above) {
            return 0;
        }

        return (float) $row->price;
    }

    public function estimateFor(string $code): string
    {
        return ShippingMethod::query()->where('code', $code)->value('estimate') ?? '';
    }

    private function query(): Builder
    {
        return ShippingMethod::query()->where('is_active', true)->orderBy('sort_order');
    }

    private function toArray(ShippingMethod $method): array
    {
        return [
            'code' => $method->code,
            'name' => $method->name,
            'price' => (float) $method->price,
            'estimate' => $method->estimate,
            'zones' => $method->zones ?? [],
            'free_above' => $method->free_above !== null ? (float) $method->free_above : null,
        ];
    }
}
