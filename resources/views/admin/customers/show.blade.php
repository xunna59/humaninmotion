@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">{{ $customer->name }}</h1>
            <p class="text-graphite mt-1">
                <a href="{{ route('admin.customers.index') }}" class="underline underline-offset-4">Customers</a>
                · {{ $customer->email }} · {{ ucwords(str_replace('_', ' ', $customer->role)) }}
            </p>
        </div>
        <div class="flex gap-2 items-center">
            <span class="badge {{ $customer->is_active ? 'badge-brass' : 'badge-sale' }}">{{ $customer->is_active ? 'Active' : 'Disabled' }}</span>
            <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_active" value="{{ $customer->is_active ? 0 : 1 }}">
                <button class="btn-bone btn-sm">{{ $customer->is_active ? 'Disable account' : 'Enable account' }}</button>
            </form>
        </div>
    </header>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            <div class="card p-5">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Overview</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-graphite">Orders</dt><dd>{{ $totalOrders }}</dd></div>
                    <div class="flex justify-between"><dt class="text-graphite">Lifetime spend</dt><dd>£{{ number_format($lifetimeSpend, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-graphite">Phone</dt><dd>{{ $customer->phone ?: '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-graphite">Joined</dt><dd>{{ $customer->created_at->format('j M Y') }}</dd></div>
                </dl>
            </div>

            <div class="card p-5">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Addresses</h2>
                @forelse ($customer->addresses as $address)
                    <div class="mb-3 text-sm">
                        <span class="badge badge-bone mb-1 capitalize">{{ $address->type }}</span>
                        <p class="text-graphite">
                            {{ $address->name }}<br>
                            {{ $address->line_one }} @if ($address->line_two) {{ $address->line_two }} @endif<br>
                            {{ $address->city }} {{ $address->postcode }}<br>
                            {{ $address->country }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-graphite">No saved addresses.</p>
                @endforelse
            </div>

            @if ($customer->wishlist?->items->isNotEmpty())
                <div class="card p-5">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Wishlist</h2>
                    <ul class="text-sm space-y-1">
                        @foreach ($customer->wishlist->items as $item)
                            <li class="text-graphite">{{ $item->product->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 card overflow-hidden">
            <div class="px-5 py-4 border-b border-ink/10">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Orders</h2>
            </div>
            <table class="table-base w-full">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Items</th>
                        <th class="text-right">Total</th>
                        <th>Status</th>
                        <th>Placed</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="font-semibold">{{ $order->order_number }}</td>
                            <td class="text-center text-graphite">{{ $order->items_count }}</td>
                            <td class="text-right">£{{ number_format((float) $order->total, 2) }}</td>
                            <td>
                                <span class="badge badge-dark">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                            </td>
                            <td class="text-xs text-graphite">{{ $order->created_at->format('j M Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-xs underline underline-offset-4 hover:text-brass">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-graphite py-10">No orders.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection