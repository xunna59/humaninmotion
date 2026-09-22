<?php

namespace App\Services\Promotions;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use Illuminate\Support\Collection;

class PromotionService
{
    public function couponFor(string $code): ?Coupon
    {
        return Coupon::query()->where('code', strtoupper(trim($code)))->first();
    }

    /**
     * Validate a coupon against a cart.
     *
     * @return array{valid: bool, message: string}
     */
    public function validate(Coupon $coupon, Cart $cart, ?int $userId = null): array
    {
        $userId = $userId ?? auth()->id();
        $subtotal = $cart->subtotal();

        if ($coupon->usage_limit !== null && $coupon->usageCount() >= $coupon->usage_limit) {
            return ['valid' => false, 'message' => 'This code has reached its usage limit.'];
        }

        if ($userId && $coupon->per_customer_limit !== null) {
            $used = CouponUsage::query()
                ->where('coupon_id', $coupon->id)
                ->where('user_id', $userId)
                ->count();
            if ($used >= $coupon->per_customer_limit) {
                return ['valid' => false, 'message' => 'You have already used this code.'];
            }
        }

        if (! $coupon->isValid(usageLimit: null, subtotal: $subtotal)) {
            return ['valid' => false, 'message' => 'This code is no longer valid.'];
        }

        if ($coupon->min_spend !== null && $subtotal < (float) $coupon->min_spend) {
            return ['valid' => false, 'message' => 'This code requires a minimum spend of ' . number_format((float) $coupon->min_spend, 2) . '.'];
        }

        if (! $this->appliesToCart($coupon, $cart)) {
            return ['valid' => false, 'message' => 'This code does not apply to the items in your bag.'];
        }

        return ['valid' => true, 'message' => 'Code applied.'];
    }

    public function appliesToCart(Coupon $coupon, Cart $cart): bool
    {
        if ($coupon->applies_to === Coupon::APPLIES_ALL) {
            return true;
        }

        $ids = $coupon->applies_ids ?? [];

        return $cart->items->contains(function (CartItem $item) use ($coupon, $ids) {
            $product = $item->variant->product;

            return match ($coupon->applies_to) {
                Coupon::APPLIES_PRODUCTS => in_array($product->id, $ids),
                Coupon::APPLIES_CATEGORIES => in_array($product->category_id, $ids),
                Coupon::APPLIES_COLLECTIONS => $product->collections()->whereIn('collections.id', $ids)->exists(),
                default => false,
            };
        });
    }

    /**
     * Calculate the discount amount for a cart.
     *
     * @return array{amount: float, line_items: Collection<array>, code: ?string}
     */
    public function calculateDiscount(Cart $cart, ?string $couponCode = null): array
    {
        $coupon = $couponCode ? $this->couponFor($couponCode) : null;
        if ($coupon) {
            $result = $this->validate($coupon, $cart);
            if (! $result['valid']) {
                return ['amount' => 0.0, 'line_items' => collect(), 'code' => null, 'invalid' => $result['message']];
            }

            $discount = $this->discountForCoupon($coupon, $cart);

            return [
                'amount' => $discount,
                'line_items' => $this->lineDiscounts($coupon, $cart, $discount),
                'code' => $coupon->code,
                'coupon_id' => $coupon->id,
            ];
        }

        return ['amount' => 0.0, 'line_items' => collect(), 'code' => null, 'coupon_id' => null];
    }

    public function discountForCoupon(Coupon $coupon, Cart $cart): float
    {
        $eligible = $this->eligibleItems($coupon, $cart);
        $eligibleSubtotal = $eligible->sum(fn ($item) => (float) $item['subtotal']);

        $discount = match ($coupon->type) {
            Coupon::TYPE_PERCENTAGE => $eligibleSubtotal * ((float) $coupon->value / 100),
            Coupon::TYPE_FIXED => min((float) $coupon->value, $eligibleSubtotal),
            Coupon::TYPE_FREE_SHIPPING => 0.0,
            default => 0.0,
        };

        if ($coupon->max_discount !== null) {
            $discount = min($discount, (float) $coupon->max_discount);
        }

        return round($discount, 2);
    }

    public function eligibleItems(Coupon $coupon, Cart $cart): Collection
    {
        return $cart->items
            ->filter(fn (CartItem $item) => $coupon->applies_to === Coupon::APPLIES_ALL || $this->appliesToCart($coupon, $cart))
            ->map(fn (CartItem $item) => [
                'item' => $item,
                'subtotal' => (float) $item->unit_price * $item->quantity,
            ]);
    }

    protected function lineDiscounts(Coupon $coupon, Cart $cart, float $totalDiscount): Collection
    {
        $eligible = $this->eligibleItems($coupon, $cart);
        $eligibleSubtotal = $eligible->sum('subtotal');

        if ($eligibleSubtotal <= 0) {
            return collect();
        }

        return $eligible->map(function ($row) use ($totalDiscount, $eligibleSubtotal) {
            $share = $row['item']->lineTotal();

            return [
                'item_id' => $row['item']->id,
                'amount' => round($totalDiscount * ($share / $eligibleSubtotal), 2),
            ];
        });
    }

    public function totalsFor(Cart $cart, ?string $couponCode, float $shipping): array
    {
        $subtotal = $cart->subtotal();
        $discount = $this->calculateDiscount($cart, $couponCode);
        $discountTotal = min($discount['amount'], $subtotal);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount['amount'],
            'discount_detail' => $discount,
            'shipping' => $shipping,
            'total' => round($subtotal - $discountTotal + $shipping, 2),
            'currency' => 'GBP',
        ];
    }
}