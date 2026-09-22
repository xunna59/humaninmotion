<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGateway;
use Illuminate\Support\Str;

/**
 * Demo-only gateway used until a real PSP is integrated.
 * Records payments in the payments table and always "succeeds",
 * but never touches real card details. Replace for production.
 */
class MockGateway implements PaymentGateway
{
    public function providerName(): string
    {
        return 'mock';
    }

    public function charge(float $amount, string $currency, array $payload = []): array
    {
        return [
            'success' => true,
            'transaction_id' => 'MOCK-' . strtoupper(Str::random(12)),
            'status' => 'succeeded',
            'amount' => $amount,
            'currency' => $currency,
            'payload' => [
                'provider' => self::class,
                'demo' => true,
                'reference' => $payload['reference'] ?? null,
            ],
        ];
    }

    public function refund(string $transactionId, float $amount, string $currency): array
    {
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'status' => 'refunded',
            'amount' => $amount,
            'currency' => $currency,
        ];
    }
}