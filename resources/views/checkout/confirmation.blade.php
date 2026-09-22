@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-16 lg:py-24 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-ok/10 text-ok mb-6">
            <x-icon name="check" size="30" />
        </div>

        <p class="eyebrow text-brass mb-3">ORDER CONFIRMED</p>
        <h1 class="display-campaign text-5xl lg:text-7xl">THANK YOU.</h1>
        <p class="mt-4 max-w-lg mx-auto text-ink/70">
            Your order <span class="font-semibold text-ink">#{{ $order->order_number }}</span> is confirmed.
            A confirmation email is on its way to <span class="font-semibold">{{ $order->customer_email }}</span>.
        </p>

        @if ($order->shippingAddress)
            <div class="mt-10 max-w-sm mx-auto border border-ink/15 bg-white p-6 text-left text-sm">
                <p class="label mb-3">ESTIMATED DELIVERY TO</p>
                <p class="font-semibold">{{ $order->shippingAddress->name }}</p>
                <p class="text-graphite leading-relaxed mt-1">
                    {{ $order->shippingAddress->line_one }}@if ($order->shippingAddress->line_two)<br>{{ $order->shippingAddress->line_two }}@endif<br>
                    {{ $order->shippingAddress->city }}@if ($order->shippingAddress->county), {{ $order->shippingAddress->county }}@endif {{ $order->shippingAddress->postcode }}<br>
                    {{ $order->shippingAddress->country }}
                </p>
                <p class="mt-3 text-[11px] uppercase tracking-wider text-graphite">via {{ \Illuminate\Support\Str::headline($order->shipping_method) }}</p>
            </div>
        @endif

        <div class="mt-10 flex flex-wrap justify-center gap-3">
            @auth
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-primary">TRACK YOUR ORDER</a>
            @endauth
            <a href="{{ route('new-in') }}" class="btn btn-outline">SHOP NEW IN</a>
        </div>

        <p class="mt-12 text-xs text-graphite">Human In Motion · Built to perform, made to last.</p>
    </div>
@endsection