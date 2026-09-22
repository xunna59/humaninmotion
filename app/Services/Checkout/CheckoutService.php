<?php

namespace App\Services\Checkout;

use App\Events\OrderPlaced;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderAddress;
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
    ) {
    }

    public function totals(Cart $cart, string $shippingMethod = 'uk_standard'): array
    {
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

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $this->generateOrderNumber(),
                'status' => Order::STATUS_CONFIRMED,
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
                'metadata' => ['checkout_data' => $data->toArrayForLog()],
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

                $variant->decrement('stock', $item->quantity);
            }

            $order->addresses()->create(['type' => 'shipping'] + $data->shipping);
            $order->addresses()->create(['type' => 'billing'] + $data->billing);
            $this->recordAddressBookEntry($data, $userId);

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

    protected function recordAddressBookEntry(CheckoutData $data, ?int $userId): void
    {
        if (! $userId) {
            return;
        }

        $existing = \App\Models\Address::query()
            ->where('user_id', $userId)
            ->where('line_one', $data->shipping['line_one'])
            ->where('postcode', $data->shipping['postcode'])
            ->exists();

        if ($existing) {
            return;
        }

        \App\Models\Address::create([
            'user_id' => $userId,
            'type' => 'shipping',
            'is_default' => false,
        ] + $data->shipping);
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
            $number = 'HM-' . strtoupper(Str::random(10));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}