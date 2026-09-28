@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
        <div>
            <h1 class="display-campaign text-4xl">Payments</h1>
            <p class="text-graphite mt-1">{{ $payments->total() }} payments</p>
        </div>
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Order, email or ref…"
                   class="input w-56">
            <select class="select" name="provider" onchange="this.form.submit()">
                <option value="">All providers</option>
                @foreach ($providers as $provider)
                    <option value="{{ $provider }}" @selected(request('provider') === $provider)>{{ ucfirst($provider) }}</option>
                @endforeach
            </select>
            <select class="select" name="status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </form>
    </header>

    <div class="card p-5 overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Provider</th>
                    <th>Reference</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                    <th>Paid</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>
                            @if ($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order) }}"
                                   class="font-mono text-brass hover:text-ink">{{ $payment->order->order_number }}</a>
                            @else
                                <span class="text-graphite">—</span>
                            @endif
                        </td>
                        <td class="text-xs">{{ $payment->order?->customer_email ?: '—' }}</td>
                        <td class="text-xs capitalize">{{ $payment->provider }}</td>
                        <td class="text-xs text-graphite" title="{{ $payment->transaction_id ?: '' }}">
                            {{ $payment->transaction_id ?: ($payment->intent_id ?: '—') }}
                        </td>
                        <td>
                            <span class="badge {{ match ($payment->status) {
                                'succeeded', 'captured' => 'badge-brass',
                                'failed' => 'badge-sale',
                                default => 'badge-dark',
                            } }}">{{ ucfirst($payment->status) }}</span>
                        </td>
                        <td class="text-right">£{{ number_format((float) $payment->amount, 2) }}</td>
                        <td class="text-xs text-graphite">{{ $payment->paid_at?->format('j M Y H:i') ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-8">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $payments->links() }}</div>
@endsection