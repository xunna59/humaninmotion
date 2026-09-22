<?php

namespace App\Services\Wishlist;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    public function add(Product $product, ?User $user = null): void
    {
        if ($user) {
            $wishlist = $this->wishlistForUser($user);
            $wishlist->items()->firstOrCreate(['product_id' => $product->id]);
            $this->clearGuest($product->id);

            return;
        }

        $ids = $this->guestIds();
        $ids[$product->id] = $product->id;
        Session::put('guest_wishlist', $ids);
    }

    public function remove(Product $product, ?User $user = null): void
    {
        if ($user) {
            $wishlist = $this->wishlistForUser($user);
            $wishlist->items()->where('product_id', $product->id)->delete();

            return;
        }

        $ids = $this->guestIds();
        unset($ids[$product->id]);
        Session::put('guest_wishlist', $ids);
    }

    public function toggle(Product $product, ?User $user = null): bool
    {
        $has = $this->has($product, $user);
        if ($has) {
            $this->remove($product, $user);
        } else {
            $this->add($product, $user);
        }

        return ! $has;
    }

    public function has(Product $product, ?User $user = null): bool
    {
        if ($user) {
            return $this->wishlistForUser($user)
                ->items()
                ->where('product_id', $product->id)
                ->exists();
        }

        return isset($this->guestIds()[$product->id]);
    }

    public function items(?User $user = null, bool $withProducts = true): \Illuminate\Support\Collection
    {
        if ($user) {
            $query = $this->wishlistForUser($user)->items();

            return $withProducts ? $query->with(['product.images', 'product.variants'])->get() : $query->get();
        }

        $ids = array_keys($this->guestIds());

        return Product::query()
            ->active()
            ->whereIn('id', $ids)
            ->with(['images', 'variants'])
            ->get()
            ->map(fn (Product $product) => $product);
    }

    public function count(?User $user = null): int
    {
        if ($user) {
            return $this->wishlistForUser($user)->items()->count();
        }

        return count($this->guestIds());
    }

    public function mergeGuestIntoUser(User $user): void
    {
        $wishlist = $this->wishlistForUser($user);
        foreach (array_keys($this->guestIds()) as $productId) {
            if (Product::query()->whereKey($productId)->exists()) {
                $wishlist->items()->firstOrCreate(['product_id' => $productId]);
            }
        }
        Session::forget('guest_wishlist');
    }

    public function wishlistForUser(User $user): Wishlist
    {
        return Wishlist::query()->firstOrCreate(['user_id' => $user->id]);
    }

    public function guestIds(): array
    {
        return Session::get('guest_wishlist', []);
    }

    public function clearGuest(int $productId): void
    {
        $ids = $this->guestIds();
        unset($ids[$productId]);
        Session::put('guest_wishlist', $ids);
    }

    public function removeItem(WishlistItem $item): void
    {
        $item->delete();
    }
}