@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">Orders</h1>
        <p class="text-graphite mt-1">{{ $orders->total() }} orders</p>
    </header>

    <form method="GET" class="card p-4 mb-6 grid sm:grid-cols-4 gap-3">
        <div>
            <label class="label" for="q">Search</label>
            <input class="input" type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Order no. or email">
        </div>
        <div>
            <label class="label" for="status">Status</label>
            <select class="select" id="status" name="status">
                <option value="">All</option>
                @foreach (['pending','confirmed','processing','packed','shipped','delivered','cancelled','refunded','partially_refunded'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label" for="payment_status">Payment</label>
            <select class="select" id="payment_status" name="payment_status">
                <option value="">All</option>
                @foreach (['unpaid','pending','paid','failed','refunded','partially_refunded'] as $status)
                    <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-3">
            <button class="btn-bone btn-sm">Filter</button>
            @if (request()->hasAny('q', 'status', 'payment_status'))
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-graphite pb-3 hover:text-ink">Clear</a>
            @endif
        </div>
    </form>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th class="text-right">Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Placed</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="font-semibold">{{ $order->order_number }}</td>
                        <td>
                            {{ $order->user?->name ?: 'Guest' }}
                            <span class="text-graphite text-xs block">{{ $order->customer_email }}</span>
                        </td>
                        <td class="text-right">£{{ number_format((float) $order->total, 2) }}</td>
                        <td>
                            <span class="badge {{ match ($order->status) {
                                    'delivered' => 'badge-brass',
                                    'cancelled', 'refunded', 'partially_refunded' => 'badge-sale',
                                    default => 'badge-dark',
                                } }}">
                                {{ ucwords(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="text-xs text-graphite capitalize">{{ str_replace('_', ' ', $order->payment_status) }}</td>
                        <td class="text-xs text-graphite">{{ $order->created_at->format('j M Y H:i') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-xs underline underline-offset-4 hover:text-brass">Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-10">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endsection