@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-8">
        <h1 class="display-campaign text-4xl">Dashboard</h1>
        <p class="text-graphite mt-1">Store overview</p>
    </header>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @php
            $stats = [
                ['Revenue', '£' . number_format($revenue, 2), 'Paid orders only'],
                ['Orders', number_format($orderCount), 'Total placed'],
                ['Customers', number_format($customerCount), 'Registered + guests'],
                ['Avg. order value', '£' . number_format($aov, 2), 'Across paid orders'],
                ['Products sold', number_format($productsSold), 'Paid units'],
            ];
        @endphp
        @foreach ($stats as [$label, $value, $hint])
            <div class="card p-5">
                <p class="label-tracked text-graphite">{{ $label }}</p>
                <p class="display-campaign text-3xl mt-2 text-ink">{{ $value }}</p>
                <p class="text-xs text-graphite mt-1">{{ $hint }}</p>
            </div>
        @endforeach
    </div>

    <div class="card p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Sales — last 14 days</h2>
            <span class="text-xs text-graphite">Total: £{{ number_format($sales->sum(), 2) }}</span>
        </div>
        <div class="flex items-end gap-1.5 h-40">
            @foreach ($sales as $day => $amount)
                @php
                    $height = $amount == 0 ? 2 : max(8, (int) round(($amount / $maxDay) * 100));
                @endphp
                <div class="flex-1 flex flex-col items-center justify-end h-full group" title="{{ $day }} — £{{ number_format($amount, 2) }}">
                    <div class="w-full bg-ink hover:bg-brass transition-colors" style="height: {{ $height }}%"></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Low stock</h2>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-brass underline underline-offset-4">Manage</a>
            </div>
            @forelse ($lowStock as $v)
                <div class="px-5 py-3 border-b border-ink/10 last:border-0 flex items-center justify-between text-sm">
                    <div>
                        <a href="{{ route('admin.products.edit', $v->product) }}" class="hover:text-brass">{{ $v->product->name }}</a>
                        <span class="text-graphite text-xs block">{{ $v->size ?: 'One size' }} · {{ $v->colour ?: '—' }}</span>
                    </div>
                    <span class="badge {{ $v->stock <= 0 ? 'badge-sale' : 'badge-brass' }}">{{ $v->stock }} left</span>
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-graphite">No low-stock variants.</p>
            @endforelse
        </div>

        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Recent orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-brass underline underline-offset-4">All</a>
            </div>
            @forelse ($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="px-5 py-3 border-b border-ink/10 last:border-0 flex items-center justify-between text-sm hover:bg-shell">
                    <div>
                        <span class="font-semibold">{{ $order->order_number }}</span>
                        <span class="text-graphite text-xs block">{{ $order->customer_email }}</span>
                    </div>
                    <span class="text-sm">£{{ number_format((float) $order->total, 2) }}</span>
                </a>
            @empty
                <p class="px-5 py-6 text-sm text-graphite">No orders yet.</p>
            @endforelse
        </div>

        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-ink/10 flex items-center justify-between">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">New customers</h2>
                <a href="{{ route('admin.customers.index') }}" class="text-xs text-brass underline underline-offset-4">All</a>
            </div>
            @forelse ($recentCustomers as $customer)
                <a href="{{ route('admin.customers.show', $customer) }}" class="px-5 py-3 border-b border-ink/10 last:border-0 flex items-center justify-between text-sm hover:bg-shell">
                    <div>
                        <span class="font-semibold">{{ $customer->name }}</span>
                        <span class="text-graphite text-xs block">{{ $customer->email }}</span>
                    </div>
                    <span class="text-xs text-graphite">{{ $customer->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="px-5 py-6 text-sm text-graphite">No customers yet.</p>
            @endforelse
        </div>
    </div>
@endsection