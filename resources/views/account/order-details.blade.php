@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12 max-w-4xl">
        <header class="mb-8">
            <p class="eyebrow text-brass mb-2">MY ACCOUNT</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">ORDER #{{ $order->order_number }}</h1>
        </header>

        @include('account._nav')

        <div class="mt-8 space-y-8">
            <div class="flex flex-wrap gap-3">
                <span class="text-[11px] uppercase tracking-wider px-3 py-1.5 border border-ink/20 text-graphite">{{ \Illuminate\Support\Str::headline($order->status) }}</span>
                <span class="text-[11px] uppercase tracking-wider px-3 py-1.5 border border-ink/20 text-graphite">{{ \Illuminate\Support\Str::headline($order->payment_status) }}</span>
                <span class="text-[11px] uppercase tracking-wider px-3 py-1.5 border border-ink/20 text-graphite">Placed {{ $order->placed_at->format('d M Y H:i') }}</span>
            </div>

            <div class="border border-ink/15 divide-y divide-ink/10">
                @foreach ($order->items as $item)
                    <div class="flex items-center gap-4 p-4">
                        @if ($item->product && $item->product->primaryImage())
                            <img src="{{ $item->product->primaryImage()->path }}" alt="{{ $item->product_name }}" class="w-16 aspect-[3/4] object-cover">
                        @endif
                        <div class="flex-1 text-sm">
                            <p class="font-semibold">{{ $item->product_name }}</p>
                            <p class="text-xs text-graphite uppercase tracking-wide">
                                @if ($item->colour){{ $item->colour }} · @endif
                                @if ($item->size)Size {{ $item->size }}@endif
                                @if ($item->sku) · {{ $item->sku }}@endif
                            </p>
                        </div>
                        <p class="text-xs text-graphite">×{{ $item->quantity }}</p>
                        <p class="text-sm font-semibold w-24 text-right">£{{ number_format($item->line_total, 2) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                @if ($order->shippingAddress)
                    <div class="border border-ink/15 p-5 text-sm">
                        <p class="label mb-2">SHIPPING ADDRESS</p>
                        <p class="font-semibold">{{ $order->shippingAddress->name }}</p>
                        <p class="text-graphite leading-relaxed">
                            {{ $order->shippingAddress->line_one }}@if ($order->shippingAddress->line_two)<br>{{ $order->shippingAddress->line_two }}@endif<br>
                            {{ $order->shippingAddress->city }}@if ($order->shippingAddress->county), {{ $order->shippingAddress->county }}@endif {{ $order->shippingAddress->postcode }}<br>
                            {{ $order->shippingAddress->country }}
                        </p>
                    </div>
                @endif

                <div class="border border-ink/15 p-5 text-sm">
                    <p class="label mb-2">ORDER SUMMARY</p>
                    <div class="space-y-1.5">
                        <div class="flex justify-between"><span class="text-graphite">Subtotal</span><span>£{{ number_format($order->subtotal, 2) }}</span></div>
                        @if ((float) $order->discount_total > 0)
                            <div class="flex justify-between"><span class="text-graphite">Discount</span><span class="text-ok">-£{{ number_format($order->discount_total, 2) }}</span></div>
                        @endif
                        <div class="flex justify-between"><span class="text-graphite">Delivery</span><span>£{{ number_format($order->shipping_total, 2) }}</span></div>
                        <div class="flex justify-between font-bold text-base pt-2 border-t border-ink/10"><span>Total</span><span>£{{ number_format($order->total, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection