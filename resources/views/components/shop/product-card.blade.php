@php
    $primary = $product->primaryImage();
    $hover = $product->hoverImage();
    $onSale = $product->isOnSale();
    $colours = collect($product->availableColours())->take(4);
    $url = route('product.show', $product->slug);
    $badges = collect([]);
    if ($product->is_new) $badges->push(['label' => 'NEW', 'class' => 'badge-dark']);
    if ($product->is_bestseller) $badges->push(['label' => 'BESTSELLER', 'class' => 'badge-bone']);
    if ($onSale) $badges->push(['label' => $product->discountPercent() . '% OFF', 'class' => 'badge-sale']);
    if ($product->is_limited) $badges->push(['label' => 'LIMITED', 'class' => 'badge-brass']);
    if ($product->is_exclusive) $badges->push(['label' => 'EXCLUSIVE', 'class' => 'badge-bone']);
    if ($product->variants->where('stock', '>', 0)->count() > 0 && $product->variants->contains(fn ($v) => $v->stock > 0 && $v->stock <= $v->low_stock_threshold)) $badges->push(['label' => 'LOW STOCK', 'class' => 'badge-outline']);
@endphp

<div class="group flex flex-col">
    <div class="relative aspect-[3/4] overflow-hidden bg-shell">
        <a href="{{ $url }}" class="absolute inset-0 block" aria-label="{{ $product->name }}">
            @if ($primary)
                <img src="{{ $primary->path }}" alt="{{ $primary->alt_text ?: $product->name }}"
                     class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300 group-hover:opacity-0"
                     loading="lazy" decoding="async">
            @endif
            @if ($hover)
                <img src="{{ $hover->path }}" alt="{{ $hover->alt_text ?: $product->name }}"
                     class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                     loading="lazy" decoding="async">
            @endif
        </a>

        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 items-start">
            @foreach ($badges->take(3) as $badge)
                <span class="{{ $badge['class'] }}">{{ $badge['label'] }}</span>
            @endforeach
        </div>

        <div class="absolute top-2.5 right-2.5">
            <livewire:wishlist-button :product="$product" :key="'w-' . $product->id" />
        </div>

        @if ($product->variants->contains(fn ($v) => $v->is_active && $v->stock > 0))
            <div class="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 p-3 bg-gradient-to-t from-ink/60 to-transparent" x-data="{}">
                <div class="max-w-[240px] mx-auto">
                    <livewire:cart-quick-add :product="$product" :wire:key="'qa-' . $product->id" />
                </div>
            </div>
        @endif
    </div>

    <div class="pt-3 flex flex-col gap-1">
        <a href="{{ $url }}" class="text-[13px] font-medium leading-snug hover:underline underline-offset-4 decoration-brass">
            {{ $product->name }}
        </a>

        <div class="flex items-center gap-2 text-[13px]">
            <span class="font-semibold @if($onSale) text-sale @endif">{{ \App\Support\Money::formatFloat($product->price) }}</span>
            @if ($onSale)
                <span class="text-graphite line-through">{{ \App\Support\Money::formatFloat($product->compare_price) }}</span>
            @endif
        </div>

        @if ($colours->isNotEmpty())
            <div class="flex items-center gap-1.5 pt-0.5" aria-label="{{ $colours->count() }} colour{{ $colours->count() === 1 ? '' : 's' }}">
                @foreach ($colours as $colour)
                    <span class="w-3 h-3 rounded-full border border-ink/15" style="background-color: {{ $colour === 'WHITE' ? '#f2f0ec' : ($colour === 'BLACK' ? '#161616' : '#8a867e') }}" aria-hidden="true"></span>
                @endforeach
            </div>
        @endif
    </div>
</div>