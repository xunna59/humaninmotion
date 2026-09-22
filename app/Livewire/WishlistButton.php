<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\Wishlist\WishlistService;
use Livewire\Component;

class WishlistButton extends Component
{
    public Product $product;

    public bool $active = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->active = app(WishlistService::class)->has($product, auth()->user());
    }

    public function toggle(): void
    {
        $this->active = app(WishlistService::class)->toggle($this->product, auth()->user());
        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}