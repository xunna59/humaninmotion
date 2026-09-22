<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGateway;
use InvalidArgumentException;

class PaymentManager
{
    protected array $gateways = [
        'mock' => MockGateway::class,
    ];

    public function gateway(?string $name = null): PaymentGateway
    {
        $name = $name ?: config('humaninmotion.payments.default', 'mock');
        $class = $this->gateways[$name] ?? null;

        if (! $class) {
            throw new InvalidArgumentException("Unknown payment gateway: {$name}");
        }

        return app($class);
    }

    public function gateways(): array
    {
        return array_keys($this->gateways);
    }
}