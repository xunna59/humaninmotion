<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutData;
use App\Services\Checkout\CheckoutService;
use App\Services\Payments\PaymentManager;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $cart = app(CartService::class)->current();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('bag');
        }

        $user = $request->user();
        $paymentManager = app(PaymentManager::class);
        $gateway = $paymentManager->gateway();
        $stripe = ($gateway->providerName() === 'stripe') ? $gateway : null;
        $stripeOperating = $stripe && $stripe->isConfigured();

        $defaultShipping = $user?->addresses()->where('type', 'shipping')->where('is_default', true)->first()
            ?? $user?->addresses()->where('type', 'shipping')->first();

        return view('checkout.index', [
            'cart' => $cart,
            'totals' => app(CheckoutService::class)->totals($cart),
            'shippingMethods' => app(ShippingService::class)->methods(),
            'defaultShipping' => $defaultShipping,
            'operatingGateway' => $stripeOperating ? 'stripe' : 'mock',
            'stripeConfigured' => $stripeOperating,
            'stripePublishableKey' => $stripeOperating ? $stripe->publishableKey() : null,
            'gatewayLabel' => $stripeOperating
                ? null
                : 'Payment is simulated in this demo. No real card data is taken.',
            'stripeNotice' => $stripe && ! $stripe->isConfigured()
                ? 'Stripe is not configured yet — add STRIPE keys to your .env to take live card payments. Orders will use the demo gateway.'
                : null,
            'title' => 'Checkout | Human In Motion',
        ]);
    }

    /**
     * Create (or refresh) the Stripe PaymentIntent for the cart total
     * including the selected shipping method. Called from the checkout page.
     */
    public function stripeIntent(Request $request): JsonResponse
    {
        $cart = app(CartService::class)->current();

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages(['checkout' => 'Your bag is empty.']);
        }

        $data = $request->validate([
            'shipping_method' => ['required', 'string', 'in:uk_standard,uk_express,europe,intl'],
        ]);

        $gateway = app(PaymentManager::class)->gateway('stripe');

        if (! $gateway->isConfigured()) {
            return response()->json([
                'error' => 'Stripe is not configured. Please add STRIPE_SECRET_KEY to your .env.',
            ], 422);
        }

        $totals = app(CheckoutService::class)->totals($cart, $data['shipping_method']);
        $intent = $gateway->createIntent($totals['total'], $totals['currency'], [
            'email' => $request->input('email') ?: null,
            'customer_email' => $request->input('email') ?: null,
        ]);

        return response()->json([
            'client_secret' => $intent['client_secret'],
            'intent_id' => $intent['id'],
            'amount' => $totals['total'],
            'currency' => $totals['currency'],
            'publishable_key' => $gateway->publishableKey(),
        ]);
    }

    public function place(Request $request): RedirectResponse
    {
        $cart = app(CartService::class)->current();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('bag');
        }

        $data = $request->validate([
            'email' => ['required', 'email'],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_line_one' => ['required', 'string', 'max:255'],
            'shipping_line_two' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_county' => ['nullable', 'string', 'max:255'],
            'shipping_postcode' => ['required', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:100'],
            'shipping_phone' => ['nullable', 'string', 'max:32'],
            'billing_same' => ['nullable', 'boolean'],
            'billing_name' => ['nullable', 'required_if:billing_same,false', 'string', 'max:255'],
            'billing_line_one' => ['nullable', 'required_if:billing_same,false', 'string', 'max:255'],
            'billing_line_two' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['nullable', 'required_if:billing_same,false', 'string', 'max:255'],
            'billing_county' => ['nullable', 'string', 'max:255'],
            'billing_postcode' => ['nullable', 'required_if:billing_same,false', 'string', 'max:20'],
            'billing_country' => ['nullable', 'required_if:billing_same,false', 'string', 'max:100'],
            'billing_phone' => ['nullable', 'string', 'max:32'],
            'shipping_method' => ['required', 'string', 'in:uk_standard,uk_express,europe,intl'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'string', 'in:mock,stripe'],
            'payment_intent_id' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $checkoutData = CheckoutData::fromRequest($data);
            $order = app(CheckoutService::class)->place(
                $cart,
                $checkoutData,
                $request->user()?->id,
            );
        } catch (RuntimeException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()])->onlyInput('email');
        }

        return redirect()->route('checkout.confirmation', $order);
    }

    public function confirmation(Request $request, Order $order): View
    {
        $order->load(['items.product', 'shippingAddress']);

        return view('checkout.confirmation', [
            'order' => $order,
            'title' => 'Order confirmed | Human In Motion',
        ]);
    }

    /**
     * Stripe webhook — finalises async payments and records refunds.
     *
     * The signature is verified with STRIPE_WEBHOOK_SECRET, so CSRF and the
     * session are intentionally bypassed. Respond 200 quickly to acknowledge.
     */
    public function webhook(Request $request): Response
    {
        try {
            $event = app(PaymentManager::class)->gateway('stripe')->constructEvent(
                $request->getContent(),
                (string) $request->header('stripe-signature'),
            );
        } catch (\Throwable $e) {
            return response('Invalid signature', Response::HTTP_BAD_REQUEST);
        }

        $object = $event->data->object;

        // charge.refunded carries the intent under ->payment_intent
        $intentId = $event->type === 'charge.refunded'
            ? ($object->payment_intent ?? null)
            : ($object->id ?? null);

        if (! $intentId) {
            return response('ok');
        }

        $payment = Payment::query()->where('intent_id', $intentId)->first();

        if (! $payment) {
            return response('ok');
        }

        $order = $payment->order;

        match ($event->type) {
            'payment_intent.succeeded' => $this->markSucceeded($payment, $order),
            'payment_intent.payment_failed' => $this->markFailed($payment, $order),
            'payment_intent.canceled' => $this->markCancelled($payment, $order),
            'charge.refunded' => $this->markRefunded($payment, $order, (float) ($object->amount_refunded / 100)),
            default => null,
        };

        return response('ok');
    }

    protected function markSucceeded(Payment $payment, ?Order $order): void
    {
        $payment->update([
            'status' => Payment::STATUS_SUCCEEDED,
            'transaction_id' => $payment->transaction_id ?: $payment->intent_id,
            'paid_at' => $payment->paid_at ?? now(),
        ]);

        $order?->refresh();
        if ($order && ! $order->isPaid()) {
            $order->update(['payment_status' => Order::PAYMENT_PAID]);
        }
    }

    protected function markFailed(Payment $payment, ?Order $order): void
    {
        $payment->update(['status' => Payment::STATUS_FAILED]);
        $order?->refresh();
        if ($order && ! $order->isPaid()) {
            $order->update(['payment_status' => Order::PAYMENT_FAILED]);
        }
    }

    protected function markCancelled(Payment $payment, ?Order $order): void
    {
        $payment->update(['status' => Payment::STATUS_FAILED]);
        $order?->refresh();
        if ($order && ! $order->isPaid()) {
            $order->update(['payment_status' => Order::PAYMENT_UNPAID]);
        }
    }

    protected function markRefunded(Payment $payment, ?Order $order, float $refundedAmount): void
    {
        $order?->refresh();
        $fullyRefunded = $order && $order->total && $refundedAmount >= (float) $order->total;

        $payment->update(['status' => Payment::STATUS_REFUNDED]);

        $order?->update([
            'payment_status' => $fullyRefunded
                ? Order::PAYMENT_REFUNDED
                : Order::PAYMENT_PARTIALLY_REFUNDED,
        ]);
    }
}
