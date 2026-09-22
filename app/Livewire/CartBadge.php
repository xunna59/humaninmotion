<?php

namespace App\Livewire;

use App\Services\Cart\CartService;
use Livewire\Component;

class CartBadge extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.cart-badge', [
            'count' => app(CartService::class)->count(),
        ]);
    }
}