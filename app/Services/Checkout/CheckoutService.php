<?php

namespace App\Services\Checkout;

use App\Events\OrderPlaced;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Services\Payments\PaymentManager;
use App\Services\Promotions\PromotionService;
use App\Services\Shipping\ShippingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        protected ShippingService $shipping,
        protected PromotionService $promotions,
        protected PaymentManager $payments,
    ) {}

    public function totals(Cart $cart, ?string $shippingMethod = null): array
    {
        $shippingMethod ??= $this->shipping->defaultCode();

        $couponCode = $cart->coupon_code;
        $discount = $this->promotions->calculateDiscount($cart, $couponCode);
        $couponFreeShipping = $discount['code']
            && ($this->promotions->couponFor($discount['code'])?->type === Coupon::TYPE_FREE_SHIPPING);

        $shipping = $couponFreeShipping ? 0.0 : $this->shipping->rateFor($shippingMethod, $cart);
        $subtotal = $cart->subtotal();

        return [
            'subtotal' => $subtotal,
            'discount' => $discount['amount'],
            'discount_code' => $discount['code'] ?? null,
            'shipping' => $shipping,
            'shipping_method' => $shippingMethod,
            'total' => round($subtotal - $discount['amount'] + $shipping, 2),
            'currency' => 'GBP',
        ];
    }

    /**
     * Create an order in a pending state before the customer is redirected
     * to the hosted gateway. Stock is untouched here — it is only deducted
     * when the payment is confirmed (see finalizeStripeOrder).
     */
    public function createPendingOrder(Cart $cart, CheckoutData $data, ?int $userId): Order
    {
        $totals = $this->totals($cart, $data->shippingMethod);

        return DB::transaction(function () use ($cart, $data, $userId, $totals) {
            return $this->buildOrder($cart, $data, $userId, $totals, Order::STATUS_PENDING);
        });
    }

    public function attachCheckoutSession(Order $order, string $sessionId): void
    {
        $order->update([
            'metadata' => array_merge($order->metadata ?? [], ['checkout_session_id' => $sessionId]),
        ]);
    }

    /**
     * Find an unresolved pending order started for this cart, so a double
     * submit (or refresh after clicking "Place order") reuses the same order
     * and Stripe session instead of stacking duplicates. Only matches when
     * the totals are identical, so a bag edit still starts a fresh order.
     */
    public function pendingOrderForCart(Cart $cart, float $total): ?Order
    {
        return Order::query()
            ->where('status', Order::STATUS_PENDING)
            ->where('payment_status', Order::PAYMENT_UNPAID)
            ->where('metadata->cart_id', $cart->id)
            ->where('created_at', '>=', now()->subHours(2))
            ->orderByDesc('created_at')
            ->get()
            ->first(fn (Order $order) => round((float) $order->total, 2) === round($total, 2));
    }

    /**
     * Confirm a paid hosted-gateway order: deduct stock, record the payment,
     * mark the order paid and send the confirmation. Safe to call concurrently
     * (idempotent): the order row is locked first, so a webhook retry racing
     * the customer's return to the success URL can only finalise once and can
     * never double-deduct stock, double-record the payment or double-email.
     *
     * @throws RuntimeException when stock is insufficient or the paid amount
     *                          does not match the order total
     */
    public function finalizeStripeOrder(Order $order, array $paymentResult, ?Cart $cart = null): Order
    {
        return DB::transaction(function () use ($order, $paymentResult, $cart) {
            // Lock the order row so concurrent finalise calls serialise here.
            /** @var Order $order */
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            // Authoritative guard, now inside the transaction and under lock.
            if ($order->isPaid() || $order->status === Order::STATUS_CANCELLED) {
                return $order->fresh();
            }

            $this->assertAmountMatches($order, $paymentResult);
            $this->deductStock($order);

            $order->payments()->create([
                'provider' => 'stripe',
                'transaction_id' => $paymentResult['transaction_id'] ?? null,
                'intent_id' => $paymentResult['intent_id'] ?? null,
                'status' => 'succeeded',
                'amount' => $order->total,
                'currency' => $order->currency,
                'payload' => [
                    'provider' => 'Stripe Checkout',
                    'session_status' => $paymentResult['status'] ?? null,
                ],
                'paid_at' => now(),
            ]);

            $order->update([
                'status' => Order::STATUS_CONFIRMED,
                'payment_status' => Order::PAYMENT_PAID,
            ]);

            $this->recordAddressBookEntry($order->fresh());
            $this->recordCouponUsage($order->fresh(), $order->user_id);

            if ($cart) {
                $this->clearCart($cart);
            }

            OrderPlaced::dispatch($order->fresh());

            return $order->fresh();
        });
    }

    protected function assertAmountMatches(Order $order, array $paymentResult): void
    {
        $expected = round((float) $order->total, 2);
        $given = round((float) ($paymentResult['amount'] ?? 0), 2);

        if ($expected <= 0 || abs($expected - $given) > 0.005) {
            throw new RuntimeException(
                'Confirmed payment amount does not match the order total. Please contact support.'
            );
        }
    }

    /**
     * @return Order the persisted order metadata wrapper
     */
    protected function buildOrder(Cart $cart, CheckoutData $data, ?int $userId, array $totals, string $status = Order::STATUS_CONFIRMED): Order
    {
        $order = Order::create([
            'user_id' => $userId,
            'order_number' => $this->generateOrderNumber(),
            'status' => $status,
            'payment_status' => Order::PAYMENT_UNPAID,
            'subtotal' => $totals['subtotal'],
            'discount_total' => $totals['discount'],
            'shipping_total' => $totals['shipping'],
            'tax_total' => 0,
            'total' => $totals['total'],
            'currency' => $totals['currency'],
            'coupon_id' => $totals['discount_code'] ? $this->promotions->couponFor($totals['discount_code'])?->id : null,
            'customer_email' => $data->email,
            'customer_phone' => $data->shipping['phone'] ?? null,
            'shipping_method' => $data->shippingMethod,
            'customer_note' => $data->customerNote,
            'placed_at' => now(),
            'metadata' => [
                'checkout_data' => $data->toArrayForLog(),
                'cart_id' => $cart->id,
            ],
        ]);

        foreach ($cart->items as $item) {
            $variant = $item->variant;
            $product = $variant->product;

            $order->items()->create([
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'product_name' => $product->name,
                'sku' => $variant->sku ?: $product->sku,
                'size' => $variant->size,
                'colour' => $variant->colour,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'line_total' => $item->lineTotal(),
            ]);
        }

        $order->addresses()->create(['type' => 'shipping'] + $data->shipping);
        $order->addresses()->create(['type' => 'billing'] + $data->billing);

        return $order;
    }

    protected function deductStock(Order $order): void
    {
        foreach ($order->items as $item) {
            /** @var ProductVariant $variant */
            $variant = ProductVariant::query()->whereKey($item->variant_id)->lockForUpdate()->first();
            if (! $variant || $variant->stock < $item->quantity) {
                throw new RuntimeException(
                    "Only {$variant?->stock} of {$item->product_name} ({$item->size}) left. Please update your bag."
                );
            }
            $variant->decrement('stock', $item->quantity);
        }
    }

    /**
     * Place an order inside a transaction.
     *
     * @throws RuntimeException when stock is insufficient
     */
    public function place(Cart $cart, CheckoutData $data, ?int $userId = null): Order
    {
        $totals = $this->totals($cart, $data->shippingMethod);

        return DB::transaction(function () use ($cart, $data, $userId, $totals) {
            // Lock order is important here — SQLite ignores row locks, but
            // production drivers will serialise on the same rows.
            foreach ($cart->items as $item) {
                /** @var ProductVariant $variant */
                $variant = ProductVariant::query()->whereKey($item->variant_id)->lockForUpdate()->first();
                if (! $variant || $variant->stock < $item->quantity) {
                    throw new RuntimeException(
                        "Only {$variant?->stock} of {$item->variant?->product->name} ({$item->variant?->size}) left. Please update your bag."
                    );
                }
            }

            $order = $this->buildOrder($cart, $data, $userId, $totals, Order::STATUS_CONFIRMED);

            $this->recordAddressBookEntry($order);
            $this->deductStock($order);

            // Payment
            $gateway = $this->payments->gateway($data->paymentMethod);
            $result = $gateway->charge($totals['total'], $totals['currency'], $data->paymentPayload);

            if (! ($result['success'] ?? false)) {
                throw new RuntimeException('Payment was declined. Please try again.');
            }

            $order->payments()->create([
                'provider' => $gateway->providerName(),
                'transaction_id' => $result['transaction_id'] ?? null,
                'status' => $result['status'] ?? Order::PAYMENT_PAID,
                'amount' => $totals['total'],
                'currency' => $totals['currency'],
                'payload' => $result['payload'] ?? null,
                'paid_at' => now(),
            ]);

            $order->update(['payment_status' => Order::PAYMENT_PAID]);

            $this->recordCouponUsage($order, $userId);
            $this->clearCart($cart);

            OrderPlaced::dispatch($order);

            return $order->fresh();
        });
    }

    protected function recordAddressBookEntry(Order $order): void
    {
        if (! $order->user_id) {
            return;
        }

        $shipping = $order->addresses()->where('type', 'shipping')->first();

        if (! $shipping) {
            return;
        }

        $existing = Address::query()
            ->where('user_id', $order->user_id)
            ->where('line_one', $shipping->line_one)
            ->where('postcode', $shipping->postcode)
            ->exists();

        if ($existing) {
            return;
        }

        Address::create([
            'user_id' => $order->user_id,
            'type' => 'shipping',
            'is_default' => false,
        ] + $shipping->only(['name', 'line_one', 'line_two', 'city', 'county', 'postcode', 'country', 'phone']));
    }

    protected function recordCouponUsage(Order $order, ?int $userId): void
    {
        if (! $order->coupon_id || (float) $order->discount_total <= 0) {
            return;
        }

        CouponUsage::create([
            'coupon_id' => $order->coupon_id,
            'order_id' => $order->id,
            'user_id' => $userId,
            'used_at' => now(),
            'discount_amount' => $order->discount_total,
        ]);
    }

    protected function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update(['coupon_code' => null]);
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'HM-'.strtoupper(Str::random(10));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}
