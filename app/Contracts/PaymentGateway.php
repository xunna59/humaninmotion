<?php

namespace App\Contracts;

interface PaymentGateway
{
    public function providerName(): string;

    /**
     * Authorise (and immediately capture where supported) the given amount.
     *
     * @param  array  $payload  e.g. payment method token/card data
     */
    public function charge(float $amount, string $currency, array $payload = []): array;

    public function refund(string $transactionId, float $amount, string $currency): array;
}