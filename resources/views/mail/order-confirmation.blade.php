@component('mail::message')
# Thanks for your order, {{ $order->customer_email }}

Your **Human In Motion** order **#{{ $order->order_number }}** is confirmed.

## Order summary

@component('mail::table')
| Item | Qty | Line total |
|:-----|:----:|-----------:|
@foreach ($order->items as $item)
| {{ $item->product_name }} @if ($item->colour)({{ $item->colour }})@endif @if ($item->size) · Size {{ $item->size }}@endif | {{ $item->quantity }} | £{{ number_format($item->line_total, 2) }} |
@endforeach
| **Subtotal** |  | **£{{ number_format($order->subtotal, 2) }}** |
@if ((float) $order->discount_total > 0)
| **Discount** |  | **-£{{ number_format($order->discount_total, 2) }}** |
@endif
| **Delivery** |  | **£{{ number_format($order->shipping_total, 2) }}** |
| **Total** |  | **£{{ number_format($order->total, 2) }}** |
@endcomponent

We'll send a shipping update as soon as your order leaves our warehouse.

@if ($order->shippingAddress)
**Ship to:**<br>
{{ $order->shippingAddress->name }}<br>
{{ $order->shippingAddress->line_one }}@if ($order->shippingAddress->line_two)<br>{{ $order->shippingAddress->line_two }}@endif<br>
{{ $order->shippingAddress->city }}@if ($order->shippingAddress->county), {{ $order->shippingAddress->county }}@endif {{ $order->shippingAddress->postcode }}<br>
{{ $order->shippingAddress->country }}
@endif

@component('mail::button', ['url' => $order->user ? url('/account/orders/' . $order->id) : url('/')])
Track your order
@endcomponent

Need help? Reply to this email and the team will help.

Thanks,<br>
**Human In Motion**
@endcomponent