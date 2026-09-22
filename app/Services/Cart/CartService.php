<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected ?Cart $cart = null;

    public function current(): ?Cart
    {
        if ($this->cart) {
            return $this->cart;
        }

        $query = Cart::query()->with([
            'items.variant.product.images',
            'items.variant.product.collections',
        ]);

        $cart = null;

        if (auth()->check()) {
            $cart = $query->where('user_id', auth()->id())->first();
        }

        if (! $cart && ($sessionId = $this->sessionId())) {
            $cart = $query->where('session_id', $sessionId)->first();
        }

        return $this->cart = $cart;
    }

    public function sessionId(): ?string
    {
        return Session::get('cart_session_id');
    }

    public function ensureSessionId(): string
    {
        $id = $this->sessionId();
        if (! $id) {
            $id = session()->getId() . '-' . str()->random(8);
            Session::put('cart_session_id', $id);
        }

        return $id;
    }

    public function getOrCreate(): Cart
    {
        if ($cart = $this->current()) {
            return $cart;
        }

        $cart = Cart::create(
            auth()->check()
                ? ['user_id' => auth()->id(), 'session_id' => $this->ensureSessionId()]
                : ['session_id' => $this->ensureSessionId()]
        );

        return $this->cart = $cart;
    }

    public function add(ProductVariant $variant, int $quantity = 1): CartItem
    {
        $cart = $this->getOrCreate();
        $price = (float) ($variant->price ?: $variant->product->price);

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->where('variant_id', $variant->id)
            ->first();

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);
            $item->refresh();
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'variant_id' => $variant->id,
                'quantity' => $quantity,
                'unit_price' => $price,
            ]);
        }

        $this->cart = $cart;

        return $item->load('variant.product.images');
    }

    public function setQuantity(CartItem $item, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            $item->delete();

            return $item;
        }

        $item->update(['quantity' => min($quantity, 99)]);

        return $item->refresh();
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function clear(): void
    {
        if ($cart = $this->current()) {
            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);
        }
    }

    public function count(): int
    {
        return $this->current()?->itemCount() ?? 0;
    }

    public function subtotal(): float
    {
        return $this->current()?->subtotal() ?? 0.0;
    }

    public function items(): \Illuminate\Support\Collection
    {
        return $this->current()?->items ?? collect();
    }

    public function item(): ?CartItem
    {
        return $this->items()->first();
    }

    public function attachUser(User|Authenticatable $user): void
    {
        $sessionCart = Cart::query()->where('session_id', $this->sessionId())->first();
        $userCart = Cart::query()->where('user_id', $user->getAuthIdentifier())->first();

        if ($sessionCart && $userCart && $sessionCart->id !== $userCart->id) {
            foreach ($sessionCart->items as $item) {
                $existing = $userCart->items()->where('variant_id', $item->variant_id)->first();
                if ($existing) {
                    $existing->update(['quantity' => $existing->quantity + $item->quantity]);
                    $item->delete();
                } else {
                    $item->update(['cart_id' => $userCart->id]);
                }
            }
            $sessionCart->delete();
        } elseif ($sessionCart && ! $userCart) {
            $sessionCart->update(['user_id' => $user->getAuthIdentifier()]);
        }

        $this->cart = null;
    }

    public function toArray(): array
    {
        $items = $this->items()->map(fn (CartItem $item) => [
            'id' => $item->id,
            'quantity' => $item->quantity,
            'line_total' => $item->lineTotal(),
            'variant' => [
                'id' => $item->variant->id,
                'size' => $item->variant->size,
                'colour' => $item->variant->colour,
                'stock' => $item->variant->stock,
            ],
            'product' => [
                'id' => $item->variant->product->id,
                'name' => $item->variant->product->name,
                'slug' => $item->variant->product->slug,
                'url' => route('product.show', $item->variant->product->slug),
                'price' => (float) $item->unit_price,
                'compare_price' => (float) $item->variant->product->compare_price,
                'image' => $item->variant->product->primaryImage()?->path,
                'hover_image' => $item->variant->product->hoverImage()?->path,
                'in_stock' => $item->variant->inStock(),
            ],
        ])->values();

        return [
            'count' => $this->count(),
            'subtotal' => $this->subtotal(),
            'items' => $items,
        ];
    }
}