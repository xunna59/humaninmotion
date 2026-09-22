<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Cart\CartService;
use Livewire\Component;

class CartQuickAdd extends Component
{
    public Product $product;

    public ?string $colour = null;

    public ?string $size = null;

    public bool $adding = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->colour = collect($product->availableColours())->first() ?: null;
    }

    public function selectColour(string $colour): void
    {
        $this->colour = $colour;
        $this->size = null;
        $first = $this->variants()->where('stock', '>', 0)->first();
        if ($first) {
            $this->size = $first->size;
        }
    }

    public function selectSize(string $size): void
    {
        $this->size = $size;
    }

    public function addToBag(): void
    {
        $this->validate([
            'size' => 'required',
        ]);

        $variant = ProductVariant::query()
            ->where('product_id', $this->product->id)
            ->where('colour', $this->colour)
            ->where('size', $this->size)
            ->with('product.images')
            ->first();

        if (! $variant || ! $variant->inStock()) {
            $this->addError('size', 'This size is currently unavailable.');
            $this->dispatch('cart-updated');

            return;
        }

        $this->adding = true;
        app(CartService::class)->add($variant, 1);
        $this->adding = false;

        $this->dispatch('cart-updated');
        $this->dispatch('cart-open');
    }

    public function variants(): \Illuminate\Support\Collection
    {
        return $this->product->variants
            ->filter(fn ($v) => $v->is_active && $v->colour === $this->colour)
            ->sortBy(fn ($v) => $this->sizeOrder($v->size));
    }

    public function sizeOrder(?string $size): int
    {
        $order = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6, '28' => 7, '30' => 8, '32' => 9, '34' => 10, '36' => 11, 'One Size' => 12];

        return $order[$size] ?? 99;
    }

    public function render()
    {
        return view('livewire.cart-quick-add', [
            'colours' => $this->product->availableColours(),
            'sizes' => $this->variants()->values(),
            'colourHexes' => \App\Models\Colour::query()->pluck('hex', 'name')->map(fn ($hex) => $hex ?? '#333')->all(),
        ]);
    }
}