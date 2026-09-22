@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12 max-w-4xl">
        <header class="mb-8">
            <p class="eyebrow text-brass mb-2">MY ACCOUNT</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">ORDERS</h1>
        </header>

        @include('account._nav')

        <div class="mt-8">
            @if ($orders->isEmpty())
                <p class="text-sm text-graphite py-10 text-center border border-dashed border-ink/15">No orders yet.</p>
            @else
                <div class="border border-ink/15 divide-y divide-ink/10">
                    @foreach ($orders as $order)
                        <a href="{{ route('account.orders.show', $order) }}" class="flex flex-wrap items-center justify-between gap-4 p-4 hover:bg-shell transition-colors">
                            <div>
                                <p class="text-sm font-semibold">#{{ $order->order_number }}</p>
                                <p class="text-xs text-graphite">{{ $order->placed_at->format('d M Y') }}</p>
                            </div>
                            <p class="text-sm">{{ $order->items()->count() }} item(s)</p>
                            <p class="text-sm font-semibold">£{{ number_format($order->total, 2) }}</p>
                            <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 inline-block border
                                @if ($order->status === 'delivered') border-ok/50 text-ok
                                @elseif ($order->status === 'cancelled' || $order->status === 'refunded') border-sale/50 text-sale
                                @else border-ink/20 text-graphite @endif">
                                {{ \Illuminate\Support\Str::headline($order->status) }}
                            </span>
                        </a>
                    @endforeach
                </div>
                {{ $orders->links() }}
            @endif
        </div>
    </div>
@endsection