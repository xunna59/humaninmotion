@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12 max-w-4xl">
        <header class="mb-8">
            <p class="eyebrow text-brass mb-2">MY ACCOUNT</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">HELLO, {{ strtoupper($user->name) }}.</h1>
        </header>

        @include('account._nav')

        <div class="grid sm:grid-cols-3 gap-4 mt-8">
            <a href="{{ route('account.orders') }}" class="border border-ink/15 p-5 hover:border-ink transition-colors group">
                <p class="display-campaign text-4xl">{{ $recentOrders->count() }}</p>
                <p class="mt-1 text-xs uppercase tracking-wider text-graphite">Orders</p>
                <p class="mt-2 text-xs text-brass group-hover:underline">View all &rarr;</p>
            </a>
            <a href="{{ route('account.addresses') }}" class="border border-ink/15 p-5 hover:border-ink transition-colors group">
                <p class="display-campaign text-4xl">{{ $addressCount }}</p>
                <p class="mt-1 text-xs uppercase tracking-wider text-graphite">Saved addresses</p>
                <p class="mt-2 text-xs text-brass group-hover:underline">Manage &rarr;</p>
            </a>
            <a href="{{ route('account.wishlist') }}" class="border border-ink/15 p-5 hover:border-ink transition-colors group">
                <p class="display-campaign text-4xl">{{ $wishlistCount }}</p>
                <p class="mt-1 text-xs uppercase tracking-wider text-graphite">Wishlist items</p>
                <p class="mt-2 text-xs text-brass group-hover:underline">View &rarr;</p>
            </a>
        </div>

        @if ($recentOrders->isNotEmpty())
            <section class="mt-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="label">RECENT ORDERS</h2>
                    <a href="{{ route('account.orders') }}" class="text-[11px] uppercase tracking-wider text-brass hover:underline">All orders</a>
                </div>
                <div class="border border-ink/15 divide-y divide-ink/10">
                    @foreach ($recentOrders as $order)
                        <a href="{{ route('account.orders.show', $order) }}" class="flex items-center justify-between gap-4 p-4 hover:bg-shell transition-colors">
                            <div>
                                <p class="text-sm font-semibold">#{{ $order->order_number }}</p>
                                <p class="text-xs text-graphite">{{ $order->placed_at->format('d M Y') }} · {{ $order->items()->count() }} item(s)</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold">£{{ number_format($order->total, 2) }}</p>
                                <p class="text-[10px] uppercase tracking-wider mt-0.5 px-2 py-0.5 inline-block border
                                    @if ($order->status === 'delivered') border-ok/50 text-ok
                                    @elseif ($order->status === 'cancelled' || $order->status === 'refunded') border-sale/50 text-sale
                                    @else border-ink/20 text-graphite @endif">
                                    {{ \Illuminate\Support\Str::headline($order->status) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection