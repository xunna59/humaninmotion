@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Order {{ $order->order_number }}</h1>
            <p class="text-graphite mt-1">
                <a href="{{ route('admin.orders.index') }}" class="underline underline-offset-4">Orders</a>
                · Placed {{ $order->created_at->format('j M Y, H:i') }}
            </p>
        </div>
        <div class="flex gap-2">
            <span class="badge badge-dark text-sm">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
            <span class="badge {{ $order->payment_status === 'paid' ? 'badge-brass' : 'badge-bone' }} text-sm">
                {{ ucwords(str_replace('_', ' ', $order->payment_status)) }}
            </span>
        </div>
    </header>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card overflow-hidden">
                <div class="px-5 py-4 border-b border-ink/10">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Items</h2>
                </div>
                <table class="table-base w-full">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th class="text-right">Unit</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Line total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product_name }}
                                    <span class="text-graphite text-xs block">{{ $item->sku }}</span>
                                </td>
                                <td class="text-xs text-graphite">
                                    {{ collect([$item->size, $item->colour])->filter()->implode(' / ') ?: '—' }}
                                </td>
                                <td class="text-right">£{{ number_format((float) $item->unit_price, 2) }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right font-semibold">£{{ number_format((float) $item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div class="card p-5">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Totals</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-graphite">Subtotal</dt><dd>£{{ number_format((float) $order->subtotal, 2) }}</dd></div>
                        @if ((float) $order->discount_total > 0)
                            <div class="flex justify-between"><dt class="text-graphite">Discount</dt><dd class="text-sale">-£{{ number_format((float) $order->discount_total, 2) }}</dd></div>
                        @endif
                        <div class="flex justify-between"><dt class="text-graphite">Shipping ({{ $order->shipping_method }})</dt><dd>£{{ number_format((float) $order->shipping_total, 2) }}</dd></div>
                        @if ((float) $order->tax_total > 0)
                            <div class="flex justify-between"><dt class="text-graphite">Tax</dt><dd>£{{ number_format((float) $order->tax_total, 2) }}</dd></div>
                        @endif
                        <div class="flex justify-between border-t border-ink/10 pt-2 font-semibold"><dt>Total</dt><dd>£{{ number_format((float) $order->total, 2) }}</dd></div>
                    </dl>
                    @if ($order->coupon)
                        <p class="text-xs text-brass mt-3">Coupon: {{ $order->coupon->code }}</p>
                    @endif
                </div>

                <div class="card p-5">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Addresses</h2>
                    <div>
                        <p class="label-tracked text-graphite mb-1">Shipping</p>
                        <p class="text-sm">{{ $order->shippingAddress?->name }}<br>
                            {{ $order->shippingAddress?->line_one }}<br>
                            @if ($order->shippingAddress?->line_two) {{ $order->shippingAddress->line_two }}<br> @endif
                            {{ $order->shippingAddress?->city }} {{ $order->shippingAddress?->postcode }}<br>
                            {{ $order->shippingAddress?->country }}<br>
                            @if ($order->shippingAddress?->phone) {{ $order->shippingAddress->phone }} @endif
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="label-tracked text-graphite mb-1">Billing</p>
                        <p class="text-sm">{{ $order->billingAddress?->name ?: 'Same as shipping' }}</p>
                    </div>
                    <div class="mt-4">
                        <p class="label-tracked text-graphite mb-1">Contact</p>
                        <p class="text-sm">{{ $order->customer_email }}</p>
                    </div>
                    @if ($order->customer_note)
                        <div class="mt-4">
                            <p class="label-tracked text-graphite mb-1">Note</p>
                            <p class="text-sm text-graphite">{{ $order->customer_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($order->payments->isNotEmpty())
                <div class="card p-5">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Payments</h2>
                    <table class="table-base w-full">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Transaction</th>
                                <th>Status</th>
                                <th class="text-right">Amount</th>
                                <th>Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->payments as $payment)
                                <tr>
                                    <td class="text-xs">{{ $payment->provider }}</td>
                                    <td class="text-xs text-graphite">{{ $payment->transaction_id }}</td>
                                    <td class="text-xs capitalize">{{ $payment->status }}</td>
                                    <td class="text-right">£{{ number_format((float) $payment->amount, 2) }}</td>
                                    <td class="text-xs text-graphite">{{ $payment->paid_at?->format('j M Y H:i') ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="card p-5 space-y-4">
                @csrf
                @method('PUT')
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Update order</h2>
                <div>
                    <label class="label" for="status">Fulfilment status</label>
                    <select class="select" id="status" name="status">
                        @foreach (['pending','confirmed','processing','packed','shipped','delivered','cancelled','refunded','partially_refunded'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="payment_status">Payment status</label>
                    <select class="select" id="payment_status" name="payment_status">
                        @foreach (['unpaid','pending','paid','failed','refunded','partially_refunded'] as $status)
                            <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="tracking_number">Tracking number</label>
                    <input class="input" id="tracking_number" name="tracking_number" value="{{ $order->shipments->first()?->tracking_number }}">
                </div>
                <div>
                    <label class="label" for="note">Note</label>
                    <textarea class="input" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                </div>
                <button class="btn-primary btn-sm w-full">Save changes</button>
            </form>

            @if ($order->shipments->isNotEmpty())
                <div class="card p-5">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-3">Shipments</h2>
                    @foreach ($order->shipments as $shipment)
                        <p class="text-sm">{{ $shipment->carrier }} — {{ $shipment->tracking_number ?: 'No tracking' }}</p>
                        <p class="text-xs text-graphite">{{ $shipment->status }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection