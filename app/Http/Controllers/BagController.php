<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Services\Cart\CartService;
use App\Services\Pricing\PricingService;
use App\Services\Promotions\PromotionService;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BagController extends Controller
{
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $variant = ProductVariant::query()
            ->with('product')
            ->where('is_active', true)
            ->whereHas('product', fn ($q) => $q->where('status', 'active'))
            ->findOrFail($request->integer('variant_id'));

        $quantity = min($request->integer('quantity'), max(1, (int) $variant->stock), 99);
        $item = app(CartService::class)->add($variant, $quantity);

        return response()->json([
            'ok' => true,
            'item_id' => $item->id,
            'quantity' => $item->quantity,
            'count' => app(CartService::class)->count(),
            'subtotal' => \App\Support\Money::formatFloat(app(CartService::class)->subtotal()),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $item = CartItem::query()->findOrFail($request->integer('item_id'));
        $quantity = max(1, min($request->integer('quantity'), 99));
        app(CartService::class)->setQuantity($item, $quantity);

        return response()->json([
            'ok' => true,
            'count' => app(CartService::class)->count(),
            'subtotal' => \App\Support\Money::formatFloat(app(CartService::class)->subtotal()),
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $item = CartItem::query()->findOrFail($request->integer('item_id'));
        app(CartService::class)->remove($item);

        return response()->json([
            'ok' => true,
            'count' => app(CartService::class)->count(),
            'subtotal' => \App\Support\Money::formatFloat(app(CartService::class)->subtotal()),
        ]);
    }

    public function applyCoupon(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'max:50']]);
        $coupon = app(PromotionService::class)->couponFor(trim($request->string('code')));

        if (! $coupon) {
            return back()->withErrors(['coupon' => 'That code is not recognised.']);
        }

        $cart = app(CartService::class)->current();
        if (! $cart) {
            return back()->withErrors(['coupon' => 'Your bag is empty.']);
        }

        $check = app(PromotionService::class)->validate($coupon, $cart, auth()->id());
        if (! $check['valid']) {
            return back()->withErrors(['coupon' => $check['message']]);
        }

        $cart->update(['coupon_code' => $coupon->code]);

        return back()->with('success', 'Coupon applied.');
    }

    public function removeCoupon(): \Illuminate\Http\RedirectResponse
    {
        $cart = app(CartService::class)->current();
        $cart?->update(['coupon_code' => null]);

        return back()->with('success', 'Coupon removed.');
    }

    public function show(Request $request)
    {
        $cart = app(CartService::class)->current();
        $pricing = app(PricingService::class);

        if (! $cart) {
            return view('shop.bag', [
                'cart' => null,
                'checkoutData' => null,
                'title' => 'Shopping Bag | Human In Motion',
            ]);
        }

        $coupon = $cart->coupon_code;
        $shipping = app(ShippingService::class)->rateFor('uk_standard', $cart);
        $couponFree = app(PromotionService::class)->couponFor($coupon ?? '');
        $shipping = $couponFree && $couponFree->type === 'free_shipping'
            ? 0
            : $shipping;

        $checkoutData = app(PromotionService::class)->totalsFor($cart, $coupon, $shipping);

        return view('shop.bag', [
            'cart' => $cart,
            'checkoutData' => $checkoutData,
            'currency' => $pricing->format(0),
            'title' => 'Shopping Bag | Human In Motion',
        ]);
    }
}