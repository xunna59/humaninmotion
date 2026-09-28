<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutData;
use App\Services\Checkout\CheckoutService;
use App\Services\Payments\PaymentManager;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'gatewayLabel' => $stripeOperating
                ? null
                : 'Payment is simulated in this demo. No real card data is taken.',
            'stripeNotice' => $stripe && ! $stripe->isConfigured()
                ? 'Stripe is not configured yet — add STRIPE keys to your .env to take live card payments. Orders will use the demo gateway.'
                : null,
            'title' => 'Checkout | Human In Motion',
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
        ]);

        $checkoutData = CheckoutData::fromRequest($data);
        $service = app(CheckoutService::class);
        $userId = $request->user()?->id;

        // Hosted Stripe Checkout: no card details ever touch this server.
        if ($checkoutData->paymentMethod === 'stripe') {
            $gateway = app(PaymentManager::class)->gateway('stripe');

            if (! $gateway->isConfigured()) {
                return back()->withErrors(['checkout' => 'Stripe is not configured yet. Please try again later.']);
            }

            try {
                $totals = $service->totals($cart, $checkoutData->shippingMethod);
                $order = $service->createPendingOrder($cart, $checkoutData, $userId);

                $session = $gateway->createCheckoutSession(
                    amount: $totals['total'],
                    currency: $totals['currency'],
                    reference: $order->order_number,
                    customerEmail: $checkoutData->email,
                    successUrl: route('checkout.return', $order),
                    cancelUrl: route('checkout.cancel', $order),
                );

                $service->attachCheckoutSession($order, $session['id']);
            } catch (RuntimeException $e) {
                return back()->withErrors(['checkout' => $e->getMessage()])->onlyInput('email');
            }

            return redirect()->away($session['url']);
        }

        try {
            $order = $service->place($cart, $checkoutData, $userId);
        } catch (RuntimeException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()])->onlyInput('email');
        }

        return redirect()->route('checkout.confirmation', $order);
    }

    /**
     * Success return from the hosted gateway. Verifies the session is paid,
     * finalises the order and shows the confirmation. If the payment is still
     * processing, the webhook will finalise it — this page lets them know.
     */
    public function returnFromGateway(Request $request, Order $order): View|RedirectResponse
    {
        if ($order->isPaid()) {
            return redirect()->route('checkout.confirmation', $order);
        }

        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->route('checkout.index');
        }

        $sessionId = $order->metadata['checkout_session_id'] ?? null;

        if (! $sessionId) {
            return redirect()->route('checkout.index');
        }

        try {
            $result = app(PaymentManager::class)->gateway('stripe')->verifyCheckoutSession($sessionId);
        } catch (RuntimeException $e) {
            return redirect()->route('checkout.index')->withErrors(['checkout' => $e->getMessage()]);
        }

        if ($result['status'] === 'paid') {
            try {
                $order = app(CheckoutService::class)->finalizeStripeOrder(
                    $order,
                    $result,
                    app(CartService::class)->current(),
                );
            } catch (RuntimeException $e) {
                $order->refresh();
                if ($order->status === Order::STATUS_PENDING) {
                    $order->update(['status' => Order::STATUS_CANCELLED]);
                }

                return redirect()->route('checkout.index')->withErrors(['checkout' => $e->getMessage()]);
            }

            return redirect()->route('checkout.confirmation', $order);
        }

        if (in_array($result['status'], ['open', 'processing'], true)) {
            return view('checkout.pending', [
                'order' => $order,
                'title' => 'Payment processing | Human In Motion',
            ]);
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return redirect()->route('checkout.index')->withErrors(['checkout' => 'Payment was not completed. Please try again.']);
    }

    public function cancelCheckout(Order $order): RedirectResponse
    {
        if ($order->status === Order::STATUS_PENDING) {
            $order->update(['status' => Order::STATUS_CANCELLED]);
        }

        return redirect()->route('checkout.index')->withErrors(['checkout' => 'You cancelled the payment. Your bag is still saved.']);
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
     * Stripe webhook — finalises hosted sessions and reconciles async events.
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

        if ($event->type === 'checkout.session.completed') {
            $this->finalizeSession((string) ($object->id ?? ''));
        }

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

    protected function finalizeSession(string $sessionId): void
    {
        if (! $sessionId) {
            return;
        }

        $order = Order::query()
            ->where('metadata->checkout_session_id', $sessionId)
            ->first();

        if (! $order || $order->isPaid()) {
            return;
        }

        $result = app(PaymentManager::class)->gateway('stripe')->verifyCheckoutSession($sessionId);

        if ($result['status'] === 'paid') {
            app(CheckoutService::class)->finalizeStripeOrder($order, $result);
        }
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
