@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12">
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('bag') }}" class="text-graphite hover:text-ink inline-flex items-center gap-1.5 text-xs uppercase tracking-wider">
                <x-icon name="chevron-left" size="14" /> Bag
            </a>
            <span class="text-graphite">/</span>
            <span class="text-xs uppercase tracking-wider">Checkout</span>
        </div>

        <h1 class="display-campaign text-4xl lg:text-6xl mb-8">CHECKOUT</h1>

        @if ($errors->any())
            <div class="mb-6 border border-sale/40 bg-sale/5 px-4 py-3 text-sm text-sale space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('checkout.place') }}" method="POST" x-data="checkoutTotals({
            subtotal: {{ $totals['subtotal'] }},
            discount: {{ $totals['discount'] }},
            shipping: {{ $totals['shipping'] }},
        })">
            @csrf

            <div class="grid lg:grid-cols-[1fr_380px] gap-10">
                <div class="space-y-10">
                    {{-- Contact --}}
                    <section>
                        <h2 class="label mb-4">01 · CONTACT</h2>
                        <div>
                            <label for="email" class="label">EMAIL ADDRESS</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email ?? $defaultShipping?->user?->email ?? '') }}" required
                                   class="field @error('email') field-invalid @enderror">
                            @error('email')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="mt-2 text-xs text-graphite">Confirmation and delivery updates go here.</p>
                    </section>

                    {{-- Shipping --}}
                    <section>
                        <h2 class="label mb-4">02 · SHIPPING ADDRESS</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="label" for="shipping_name">FULL NAME</label>
                                <input id="shipping_name" name="shipping_name" value="{{ old('shipping_name', $defaultShipping->name ?? '') }}" required class="field">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="shipping_line_one">ADDRESS LINE 1</label>
                                <input id="shipping_line_one" name="shipping_line_one" value="{{ old('shipping_line_one', $defaultShipping->line_one ?? '') }}" required class="field">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="shipping_line_two">ADDRESS LINE 2 (OPTIONAL)</label>
                                <input id="shipping_line_two" name="shipping_line_two" value="{{ old('shipping_line_two', $defaultShipping->line_two ?? '') }}" class="field">
                            </div>
                            <div>
                                <label class="label" for="shipping_city">CITY</label>
                                <input id="shipping_city" name="shipping_city" value="{{ old('shipping_city', $defaultShipping->city ?? '') }}" required class="field">
                            </div>
                            <div>
                                <label class="label" for="shipping_county">COUNTY (OPTIONAL)</label>
                                <input id="shipping_county" name="shipping_county" value="{{ old('shipping_county', $defaultShipping->county ?? '') }}" class="field">
                            </div>
                            <div>
                                <label class="label" for="shipping_postcode">POSTCODE</label>
                                <input id="shipping_postcode" name="shipping_postcode" value="{{ old('shipping_postcode', $defaultShipping->postcode ?? '') }}" required class="field">
                            </div>
                            <div>
                                <label class="label" for="shipping_country">COUNTRY</label>
                                <input id="shipping_country" name="shipping_country" value="{{ old('shipping_country', $defaultShipping->country ?? 'United Kingdom') }}" required class="field">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="shipping_phone">PHONE (OPTIONAL)</label>
                                <input id="shipping_phone" name="shipping_phone" type="tel" value="{{ old('shipping_phone', $defaultShipping->phone ?? '') }}" class="field">
                            </div>
                        </div>
                    </section>

                    {{-- Billing --}}
                    <section>
                        <h2 class="label mb-4">03 · BILLING</h2>
                        <label class="flex items-center gap-3 cursor-pointer select-none mb-4" x-data>
                            <input type="checkbox" name="billing_same" value="1" checked
                                   @change="document.getElementById('billing-fields').classList.toggle('hidden', this.checked)"
                                   class="peer sr-only">
                            <span class="w-5 h-5 border border-ink/30 flex items-center justify-center text-transparent peer-checked:bg-ink peer-checked:border-ink peer-checked:text-bone text-[12px] leading-none">&check;</span>
                            <span class="text-sm">Billing address same as shipping</span>
                        </label>

                        <div id="billing-fields" class="hidden grid sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="label" for="billing_name">FULL NAME</label>
                                <input id="billing_name" name="billing_name" value="{{ old('billing_name') }}" class="field">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="billing_line_one">ADDRESS LINE 1</label>
                                <input id="billing_line_one" name="billing_line_one" value="{{ old('billing_line_one') }}" class="field">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="billing_line_two">ADDRESS LINE 2 (OPTIONAL)</label>
                                <input id="billing_line_two" name="billing_line_two" value="{{ old('billing_line_two') }}" class="field">
                            </div>
                            <div>
                                <label class="label" for="billing_city">CITY</label>
                                <input id="billing_city" name="billing_city" value="{{ old('billing_city') }}" class="field">
                            </div>
                            <div>
                                <label class="label" for="billing_county">COUNTY (OPTIONAL)</label>
                                <input id="billing_county" name="billing_county" value="{{ old('billing_county') }}" class="field">
                            </div>
                            <div>
                                <label class="label" for="billing_postcode">POSTCODE</label>
                                <input id="billing_postcode" name="billing_postcode" value="{{ old('billing_postcode') }}" class="field">
                            </div>
                            <div>
                                <label class="label" for="billing_country">COUNTRY</label>
                                <input id="billing_country" name="billing_country" value="{{ old('billing_country') }}" class="field">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="billing_phone">PHONE (OPTIONAL)</label>
                                <input id="billing_phone" name="billing_phone" type="tel" value="{{ old('billing_phone') }}" class="field">
                            </div>
                        </div>
                    </section>

                    {{-- Delivery --}}
                    <section>
                        <h2 class="label mb-4">04 · DELIVERY</h2>
                        <div class="space-y-3">
                            @foreach ($shippingMethods as $method)
                                @php
                                    $free = $method['free_above'] !== null && $totals['subtotal'] >= $method['free_above'];
                                    $price = $free ? 0 : $method['price'];
                                @endphp
                                <label class="group flex items-center gap-4 border p-4 cursor-pointer transition-colors has-checked:border-ink border-ink/15">
                                    <input type="radio" name="shipping_method" value="{{ $method['code'] }}"
                                           @checked($method['code'] === 'uk_standard')
                                           data-shipping-price="{{ $price }}"
                                           @change="setShipping({{ $price }})"
                                           class="peer sr-only">
                                    <span class="w-4 h-4 rounded-full border-2 border-ink/30 group-has-checked:border-ink flex items-center justify-center">
                                        <span class="w-2 h-2 rounded-full bg-ink opacity-0 group-has-checked:opacity-100"></span>
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold">{{ $method['name'] }}</p>
                                        <p class="text-xs text-graphite">{{ $method['estimate'] }}</p>
                                    </div>
                                    <span class="text-sm font-semibold">
                                        {{ $free ? 'FREE' : \App\Support\Money::formatFloat($price) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </section>

                    {{-- Payment --}}
                    <section>
                        <h2 class="label mb-4">05 · PAYMENT</h2>
                        @if ($gatewayLabel)
                            <div class="mb-4 border border-ink/10 bg-shell px-4 py-3 text-xs text-graphite">
                                {{ $gatewayLabel }}
                            </div>
                        @endif
                        <div class="space-y-3">
                            <label class="group flex items-center gap-4 border p-4 cursor-pointer transition-colors has-checked:border-ink border-ink/15">
                                <input type="radio" name="payment_method" value="mock" checked class="peer sr-only">
                                <span class="w-4 h-4 rounded-full border-2 border-ink/30 group-has-checked:border-ink flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-ink opacity-0 group-has-checked:opacity-100"></span>
                                </span>
                                <span class="flex items-center gap-2 flex-1">
                                    <x-icon name="lock" size="16" />
                                    <span class="text-sm font-semibold">Demo payment (card numbers are never processed)</span>
                                </span>
                            </label>
                            <p class="text-xs text-graphite">Card payment is simulated in this demo build. No payment data is collected or stored.</p>
                        </div>
                    </section>
                </div>

                {{-- Summary --}}
                <aside class="lg:sticky lg:top-24 h-fit">
                    <div class="border border-ink/15 bg-white p-6 space-y-5">
                        <h2 class="label">ORDER SUMMARY</h2>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach ($cart->items as $item)
                                <div class="flex items-start gap-3">
                                    <div class="relative w-14 shrink-0 aspect-[3/4] bg-shell overflow-hidden">
                                        <img src="{{ $item->variant->product->primaryImage()?->path }}" alt="{{ $item->variant->product->name }}" loading="lazy" class="w-full h-full object-cover">
                                        <span class="absolute -top-0 -right-0 w-5 h-5 bg-ink text-bone text-[10px] flex items-center justify-center font-bold">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="flex-1 text-xs">
                                        <p class="font-semibold">{{ $item->variant->product->name }}</p>
                                        <p class="text-graphite mt-0.5 uppercase tracking-wide">{{ $item->variant->colour }} @if ($item->variant->size) · {{ $item->variant->size }} @endif</p>
                                    </div>
                                    <span class="text-xs font-semibold">{{ \App\Support\Money::formatFloat($item->lineTotal()) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-2 text-sm border-t border-ink/10 pt-4">
                            <div class="flex justify-between">
                                <span class="text-graphite">Subtotal</span>
                                <span class="font-semibold" x-text="money(subtotal)"></span>
                            </div>
                            <div class="flex justify-between" x-show="discount > 0" x-cloak>
                                <span class="text-graphite">Discount</span>
                                <span class="font-semibold text-ok" x-text="'-' + money(discount)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-graphite">Delivery</span>
                                <span class="font-semibold" x-text="shipping > 0 ? money(shipping) : 'FREE'"></span>
                            </div>
                            <div class="flex justify-between border-t border-ink/10 pt-3 text-base font-bold">
                                <span>Total</span>
                                <span :data-total="String(total())" x-text="money(total())"></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block justify-center">PLACE ORDER</button>
                        <p class="text-xs text-graphite text-center flex items-center justify-center gap-1.5">
                            <x-icon name="lock" size="14" /> Secure checkout
                        </p>
                    </div>
                </aside>
            </div>
        </form>
    </div>
@endsection