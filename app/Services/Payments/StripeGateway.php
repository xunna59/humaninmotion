<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGateway;
use RuntimeException;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

/**
 * Live payment gateway using Stripe PaymentIntent + Payment Element.
 *
 * Keys come from .env (STRIPE_SECRET_KEY / STRIPE_PUBLISHABLE_KEY) via
 * config/humaninmotion.php. Card details never touch this server — the
 * client confirms the intent (via Stripe.js) and we only verify + record.
 */
class StripeGateway implements PaymentGateway
{
    protected bool $configured;

    protected ?StripeClient $client = null;

    public function __construct()
    {
        $this->configured = filled(config('humaninmotion.payments.gateways.stripe.secret_key'));
    }

    public function providerName(): string
    {
        return 'stripe';
    }

    public function isConfigured(): bool
    {
        return $this->configured;
    }

    public function publishableKey(): ?string
    {
        return config('humaninmotion.payments.gateways.stripe.publishable_key');
    }

    /**
     * Verify a webhook event using the endpoint secret. Returns the parsed
     * event on success, throws RuntimeException otherwise.
     *
     * @throws RuntimeException
     */
    public function constructEvent(string $payload, string $signature): Event
    {
        $secret = config('humaninmotion.payments.gateways.stripe.webhook_secret');

        if (! $this->configured || blank($secret)) {
            throw new RuntimeException('Stripe webhook secret is not configured yet.');
        }

        try {
            return Webhook::constructEvent($payload, $signature, $secret);
        } catch (SignatureVerificationException $e) {
            throw new RuntimeException('Stripe webhook signature verification failed.');
        }
    }

    protected function client(): StripeClient
    {
        if (! $this->configured) {
            throw new RuntimeException('Stripe is not configured. Add STRIPE_SECRET_KEY to your .env file.');
        }

        return $this->client ??= new StripeClient([
            'api_key' => config('humaninmotion.payments.gateways.stripe.secret_key'),
        ]);
    }

    /**
     * Create a PaymentIntent for the current cart total. The client mounts
     * a Payment Element against the returned client_secret.
     *
     * @return array{id: string, client_secret: string, amount: int}
     */
    public function createIntent(float $amount, string $currency, array $payload = []): array
    {
        try {
            $intent = $this->client()->paymentIntents->create([
                'amount' => $this->toMinorUnits($amount, $currency),
                'currency' => strtolower($currency),
                'payment_method_types' => ['card'],
                'description' => 'Human In Motion order',
                'metadata' => array_merge([
                    'integration' => 'humaninmotion-checkout',
                ], array_filter($payload)),
            ]);
        } catch (ApiErrorException $e) {
            throw new RuntimeException('Stripe could not create a payment: '.$e->getMessage());
        }

        return [
            'id' => $intent->id,
            'client_secret' => $intent->client_secret,
            'amount' => $intent->amount,
        ];
    }

    /**
     * Verify a client-side confirmed PaymentIntent and record it as paid.
     *
     * @param  array  $payload  requires 'intent_id'
     */
    public function charge(float $amount, string $currency, array $payload = []): array
    {
        $intentId = $payload['intent_id'] ?? null;

        if (! $intentId) {
            throw new RuntimeException('Missing payment method. Please try again.');
        }

        try {
            $intent = $this->client()->paymentIntents->retrieve($intentId);
        } catch (ApiErrorException $e) {
            throw new RuntimeException('Stripe could not verify the payment: '.$e->getMessage());
        }

        if ($intent->amount !== $this->toMinorUnits($amount, $currency)) {
            throw new RuntimeException('Order total changed since payment. Please try again.');
        }

        if ($intent->status === 'requires_capture') {
            try {
                $this->client()->paymentIntents->capture($intentId);
                $intent = $this->client()->paymentIntents->retrieve($intentId);
            } catch (ApiErrorException $e) {
                throw new RuntimeException('Stripe could not capture the payment: '.$e->getMessage());
            }
        }

        if (! in_array($intent->status, ['succeeded', 'processing'], true)) {
            throw new RuntimeException(
                $intent->status === 'canceled' ? 'Payment was cancelled. Please try again.' : 'Payment was not authorised. Please try again.'
            );
        }

        return [
            'success' => true,
            'transaction_id' => $intent->id,
            'intent_id' => $intent->id,
            'status' => 'succeeded',
            'amount' => $amount,
            'currency' => $currency,
            'payload' => [
                'provider' => self::class,
                'intent_status' => $intent->status,
                'payment_method' => $intent->payment_method ?? null,
            ],
        ];
    }

    public function refund(string $transactionId, float $amount, string $currency): array
    {
        try {
            $refund = $this->client()->refunds->create([
                'payment_intent' => $transactionId,
                'amount' => $this->toMinorUnits($amount, $currency),
            ]);
        } catch (ApiErrorException $e) {
            throw new RuntimeException('Stripe could not refund the payment: '.$e->getMessage());
        }

        return [
            'success' => true,
            'transaction_id' => $refund->id,
            'status' => 'refunded',
            'amount' => $amount,
            'currency' => $currency,
        ];
    }

    public function toMinorUnits(float $amount, string $currency): int
    {
        return match (strtoupper($currency)) {
            'JPY', 'KRW', 'VND' => (int) round($amount),
            default => (int) round($amount * 100),
        };
    }
}
