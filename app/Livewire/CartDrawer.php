<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Services\Cart\CartService;
use App\Services\Promotions\PromotionService;
use App\Services\Shipping\ShippingService;
use Livewire\Component;

class CartDrawer extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    public function getCartProperty(): \App\Services\Cart\CartService
    {
        return app(CartService::class);
    }

    public function updateQuantity(int $itemId, int $quantity): void
    {
        $item = CartItem::with('variant.product')->find($itemId);
        if (! $item) {
            return;
        }
        if ($quantity <= 0) {
            $item->delete();
            $this->dispatch('cart-updated');

            return;
        }

        $stock = $item->variant?->stock ?? 0;
        $item->update(['quantity' => min($quantity, max(1, $stock), 99)]);
        $this->dispatch('cart-updated');
    }

    public function remove(int $itemId): void
    {
        CartItem::find($itemId)?->delete();
        $this->dispatch('cart-updated');
    }

    public function clear(): void
    {
        app(CartService::class)->clear();
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $cart = $this->cart->current();
        $items = $cart?->items ?? collect();
        $subtotal = $cart?->subtotal() ?? 0;

        $shippingService = app(ShippingService::class);
        $shipping = $cart ? $shippingService->rateFor('uk_standard', $cart) : 0;
        $promotion = app(PromotionService::class);
        $coupon = $cart?->coupon_code;
        $couponFree = $coupon ? $promotion->couponFor($coupon) : null;
        if ($couponFree && $couponFree->type === 'free_shipping') {
            $shipping = 0;
        }
        $discount = $cart ? $promotion->calculateDiscount($cart, $coupon) : ['amount' => 0];
        $total = round($subtotal - (float) $discount['amount'] + $shipping, 2);

        $hasBag = $items->isNotEmpty();

        return view('livewire.cart-drawer', [
            'cart' => $cart,
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount['amount'],
            'total' => $total,
            'hasBag' => $hasBag,
        ]);
    }
}