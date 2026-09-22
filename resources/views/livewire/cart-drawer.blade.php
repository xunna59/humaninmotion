<div x-data="drawer(false)" @cart-open.window="open = true">
    <div class="relative z-100">
        <template x-show="open" x-cloak>
            <div class="fixed inset-0 z-[60] bg-ink/50" x-transition.opacity.duration.150ms @click="open = false"></div>
        </template>

        <aside x-show="open" x-cloak
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
               class="fixed inset-y-0 right-0 z-[70] w-full max-w-[420px] bg-bone flex flex-col shadow-2xl"
               role="dialog" aria-modal="true" aria-label="Shopping bag">

            <div class="flex items-center justify-between px-5 h-16 border-b border-ink/10 shrink-0">
                <p class="eyebrow text-ink">{{ $hasBag ? 'YOUR BAG · ' . $items->sum('quantity') : 'YOUR BAG' }}</p>
                <button class="p-1.5 text-ink/70 hover:text-ink" @click="open = false" aria-label="Close bag">
                    <x-icon name="close" size="22" />
                </button>
            </div>

            @if ($hasBag)
                <div class="flex-1 overflow-y-auto px-5 py-4 divide-y divide-ink/10">
                    @foreach ($items as $item)
                        <div class="flex gap-4 py-4 items-start" wire:key="item-{{ $item->id }}">
                            <a href="{{ route('product.show', $item->variant->product->slug) }}" class="w-20 h-24 shrink-0 bg-shell overflow-hidden">
                                @if ($item->variant->product->primaryImage())
                                    <img src="{{ $item->variant->product->primaryImage()->path }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover" loading="lazy">
                                @endif
                            </a>
                            <div class="flex-1 space-y-1">
                                <div class="flex items-start justify-between gap-2">
                                    <a href="{{ route('product.show', $item->variant->product->slug) }}" class="text-[13px] font-semibold leading-snug">{{ $item->variant->product->name }}</a>
                                    <button class="text-ink/50 hover:text-sale transition-colors p-0.5" wire:click="remove({{ $item->id }})" wire:loading.attr="disabled" aria-label="Remove">
                                        <x-icon name="trash" size="16" />
                                    </button>
                                </div>
                                <p class="text-[12px] text-graphite">
                                    @if ($item->variant->colour)<span class="uppercase tracking-wide">{{ $item->variant->colour }}</span> · @endif
                                    @if ($item->variant->size)<span>Size {{ $item->variant->size }}</span>@endif
                                </p>
                                <div class="flex items-center justify-between pt-1.5">
                                    <div class="flex items-center border border-ink/20" x-data="qty({{ $item->quantity }}, {{ max(99, $item->variant->stock > 0 ? $item->variant->stock : 99) }})">
                                        <button class="w-8 h-8 flex items-center justify-center text-ink/60 hover:text-ink" @click="decrement(); $wire.updateQuantity({{ $item->id }}, qty)" aria-label="Decrease">
                                            <x-icon name="minus" size="14" />
                                        </button>
                                        <span class="w-8 text-center text-[13px]" x-text="qty"></span>
                                        <button class="w-8 h-8 flex items-center justify-center text-ink/60 hover:text-ink" @click="increment(); $wire.updateQuantity({{ $item->id }}, qty)" aria-label="Increase">
                                            <x-icon name="plus" size="14" />
                                        </button>
                                    </div>
                                    <p class="text-[13px] font-semibold">{{ \App\Support\Money::formatFloat($item->lineTotal()) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-ink/10 px-5 py-4 space-y-3 shrink-0 bg-bone">
                    <div class="flex justify-between text-[13px]">
                        <span class="text-graphite">Subtotal</span>
                        <span class="font-semibold">{{ \App\Support\Money::formatFloat($subtotal) }}</span>
                    </div>
                    @if ($discount > 0)
                        <div class="flex justify-between text-[13px]">
                            <span class="text-graphite">Discount</span>
                            <span class="font-semibold text-ok">-{{ \App\Support\Money::formatFloat($discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-[13px]">
                        <span class="text-graphite">Delivery</span>
                        <span class="font-semibold">{{ $shipping > 0 ? \App\Support\Money::formatFloat($shipping) : 'FREE' }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold">
                        <span>Total</span>
                        <span>{{ \App\Support\Money::formatFloat($total) }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" @click="open = false">Checkout</a>
                    <a href="{{ route('bag') }}" class="btn btn-outline btn-block" @click="open = false">View Bag</a>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center gap-4 text-center px-8">
                    <x-icon name="bag" size="40" class="text-ink/30" />
                    <div>
                        <p class="display-campaign text-2xl mb-1">YOUR BAG IS WAITING.</p>
                        <p class="text-sm text-graphite">Discover the latest Human In Motion pieces.</p>
                    </div>
                    <a href="{{ route('new-in') }}" class="btn btn-primary btn-sm" @click="open = false">SHOP NEW IN</a>
                </div>
            @endif
        </aside>
    </div>
</div>