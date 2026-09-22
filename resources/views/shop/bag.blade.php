@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12">
        <h1 class="display-campaign text-4xl lg:text-6xl mb-8">YOUR BAG</h1>

        @if (session('success'))
            <div class="mb-6 border border-ok/40 bg-ok/10 text-ok px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        @if (! $cart || $cart->items->isEmpty())
            <div class="py-16 text-center">
                <x-icon name="bag" size="44" class="mx-auto text-ink/30" />
                <p class="display-campaign text-3xl mt-4 mb-2">YOUR BAG IS WAITING.</p>
                <p class="text-sm text-graphite mb-8">Discover the latest Human In Motion pieces.</p>
                <a href="{{ route('new-in') }}" class="btn btn-primary">SHOP NEW IN</a>
            </div>
        @else
            <div class="grid lg:grid-cols-[1fr_340px] gap-10">
                <div>
                    <div class="divide-y divide-ink/10 border-y border-ink/10">
                        @foreach ($cart->items as $item)
                            @php $product = $item->variant->product; @endphp
                            <div class="flex gap-5 py-6" wire:key="bag-item-{{ $item->id }}">
                                <a href="{{ route('product.show', $product->slug) }}" class="w-24 md:w-28 shrink-0 aspect-[3/4] bg-shell overflow-hidden">
                                    <img src="{{ $product->primaryImage()?->path }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-full object-cover">
                                </a>

                                <div class="flex-1 flex flex-col">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <a href="{{ route('product.show', $product->slug) }}" class="font-semibold text-sm md:text-base hover:underline underline-offset-4">{{ $product->name }}</a>
                                            <p class="text-xs text-graphite mt-1 uppercase tracking-wide">
                                                @if ($item->variant->colour){{ $item->variant->colour }} · @endif
                                                @if ($item->variant->size)Size {{ $item->variant->size }}@endif
                                            </p>
                                            @if ($item->variant->isOutOfStock())
                                                <p class="text-sale text-xs mt-1">Out of stock</p>
                                            @elseif ($item->variant->isLowStock())
                                                <p class="text-[11px] text-amber-700 mt-1">Low stock — only {{ $item->variant->stock }} left</p>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-sm">{{ \App\Support\Money::formatFloat($item->lineTotal()) }}</p>
                                    </div>

                                    <div class="mt-auto pt-4 flex items-center justify-between">
                                        <form action="{{ route('bag.update') }}" method="POST" class="flex items-center border border-ink/20">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <button type="button" class="w-9 h-9 flex items-center justify-center text-ink/60 hover:text-ink" onclick="const i=this.parentElement.querySelector('input[name=quantity]'); i.value=Math.max(1,+i.value-1); this.parentElement.requestSubmit()" aria-label="Decrease">
                                                <x-icon name="minus" size="14" />
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99"
                                                   class="w-12 text-center text-sm h-9 bg-transparent focus:outline-none [appearance:textfield]" aria-label="Quantity">
                                            <button type="button" class="w-9 h-9 flex items-center justify-center text-ink/60 hover:text-ink" onclick="const i=this.parentElement.querySelector('input[name=quantity]'); i.value=Math.min(99,+i.value+1); this.parentElement.requestSubmit()" aria-label="Increase">
                                                <x-icon name="plus" size="14" />
                                            </button>
                                            <button type="submit" class="sr-only">Update</button>
                                        </form>

                                        <form action="{{ route('bag.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <button type="submit" class="text-[11px] uppercase tracking-wider text-graphite hover:text-sale transition-colors inline-flex items-center gap-1.5">
                                                <x-icon name="trash" size="14" /> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <aside class="lg:sticky lg:top-24 h-fit">
                    <div class="border border-ink/15 bg-white p-6 space-y-4">
                        <h2 class="label">ORDER SUMMARY</h2>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-graphite">Subtotal</span>
                                <span class="font-semibold">{{ \App\Support\Money::formatFloat($checkoutData['subtotal']) }}</span>
                            </div>
                            @if ($checkoutData['discount'] > 0)
                                <div class="flex justify-between">
                                    <span class="text-graphite">Discount @if ($cart->coupon_code)({{ $cart->coupon_code }})@endif</span>
                                    <span class="font-semibold text-ok">-{{ \App\Support\Money::formatFloat($checkoutData['discount']) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-graphite">Delivery</span>
                                <span class="font-semibold">{{ $checkoutData['shipping'] > 0 ? \App\Support\Money::formatFloat($checkoutData['shipping']) : 'FREE' }}</span>
                            </div>
                            <div class="flex justify-between border-t border-ink/10 pt-3 text-base font-bold">
                                <span>Total</span>
                                <span>{{ \App\Support\Money::formatFloat($checkoutData['total']) }}</span>
                            </div>
                        </div>

                        @if ($checkoutData['shipping'] > 0 && $checkoutData['subtotal'] < 100)
                            <p class="text-[11px] text-graphite">
                                Add {{ \App\Support\Money::formatFloat(max(0, 100 - $checkoutData['subtotal'])) }} more for free delivery.
                            </p>
                        @endif

                        <div class="space-y-2">
                            @if ($cart->coupon_code)
                                <div class="flex items-center justify-between border border-ok/30 bg-ok/5 px-3 py-2 text-sm">
                                    <span class="font-semibold">{{ $cart->coupon_code }}</span>
                                    <form action="{{ route('bag.coupon.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-graphite hover:text-sale text-xs uppercase tracking-wider">Remove</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('bag.coupon') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="code" placeholder="GIFT CODE" value="{{ old('code') }}" class="field flex-1 uppercase" aria-label="Gift code">
                                    <button type="submit" class="btn btn-outline btn-sm shrink-0">Apply</button>
                                </form>
                            @endif
                            @error('coupon')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <form action="{{ route('checkout.index') }}" method="GET">
                            <button type="submit" class="btn btn-primary btn-block justify-center">CHECKOUT</button>
                        </form>
                        <a href="{{ route('shop') }}" class="btn btn-outline btn-block justify-center">CONTINUE SHOPPING</a>
                    </div>
                </aside>
            </div>
        @endif
    </div>
@endsection