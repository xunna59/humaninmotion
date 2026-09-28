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
 * Live payment gateway using Stripe's hosted Checkout page.
 *
 * Keys come from .env (STRIPE_SECRET_KEY / STRIPE_PUBLISHABLE_KEY) via
 * config/humaninmotion.php. Customers are redirected to a Stripe Checkout
 * session, so card details never touch this server; the session is verified
 * on return and via the webhook.
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

    /**
     * Create a Stripe Checkout (hosted) session. The customer is redirected
     * to the returned url and pays entirely on Stripe's site, so no card
     * details ever touch this server.
     *
     * @return array{id: string, url: string, amount: int}
     */
    public function createCheckoutSession(
        float $amount,
        string $currency,
        string $reference,
        ?string $customerEmail,
        string $successUrl,
        string $cancelUrl,
    ): array {
        try {
            $session = $this->client()->checkout->sessions->create([
                'mode' => 'payment',
                'client_reference_id' => $reference,
                'customer_email' => $customerEmail,
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'unit_amount' => $this->toMinorUnits($amount, $currency),
                        'product_data' => [
                            'name' => 'Human In Motion order '.$reference,
                        ],
                    ],
                ]],
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'integration' => 'humaninmotion-checkout',
                    'order_reference' => $reference,
                ],
                'submit_type' => 'pay',
            ]);
        } catch (ApiErrorException $e) {
            throw new RuntimeException('Stripe could not start checkout: '.$e->getMessage());
        }

        return [
            'id' => $session->id,
            'url' => $session->url,
            'amount' => $session->amount_total,
        ];
    }

    /**
     * Fetch a Checkout session and confirm it was paid before finalising an
     * order. Also returns the underlying PaymentIntent id for reconciliation.
     *
     * @return array{success: bool, transaction_id: string, intent_id: ?string, status: string, amount: float, currency: string}
     */
    public function verifyCheckoutSession(string $sessionId): array
    {
        try {
            $session = $this->client()->checkout->sessions->retrieve($sessionId);
        } catch (ApiErrorException $e) {
            throw new RuntimeException('Stripe could not verify the checkout: '.$e->getMessage());
        }

        $status = $session->payment_status ?? 'unpaid';

        return [
            'success' => $status === 'paid',
            'transaction_id' => $session->id,
            'intent_id' => $session->payment_intent ?? null,
            'status' => $status,
            'amount' => $session->amount_total / 100,
            'currency' => strtoupper((string) $session->currency),
        ];
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
     * Verify a client-side confirmed PaymentIntent and record it as paid.
     * Kept to satisfy the PaymentGateway contract (mock-style charge path).
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
