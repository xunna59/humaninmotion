<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::query()->with(['user:id,name,email']);

        if ($term = trim($request->string('q')->toString())) {
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                    ->orWhere('customer_email', 'like', "%{$term}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        return view('admin.orders.index', [
            'orders' => $query->orderByDesc('created_at')->paginate(25)->withQueryString(),
            'title' => 'Orders',
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.variant', 'items.product', 'shippingAddress', 'billingAddress', 'payments', 'shipments', 'coupon']);

        return view('admin.orders.show', [
            'order' => $order,
            'title' => 'Order ' . $order->order_number,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,packed,shipped,delivered,cancelled,refunded,partially_refunded'],
            'payment_status' => ['required', 'in:unpaid,pending,paid,failed,refunded,partially_refunded'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $old = ['status' => $order->status, 'payment_status' => $order->payment_status];
        $order->update([
            'status' => $data['status'],
            'payment_status' => $data['payment_status'],
        ]);

        if (! empty($data['tracking_number'])) {
            Shipment::query()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'carrier' => 'Demo Courier',
                    'tracking_number' => $data['tracking_number'],
                    'method' => $order->shipping_method,
                    'status' => $data['status'] === 'delivered' ? 'delivered' : 'shipped',
                    'shipped_at' => $order->shipments()->exists() ? $order->shipments()->first()->shipped_at : now(),
                ],
            );
        }

        AuditLog::record('admin.order.updated', $order, [
            'from' => $old,
            'to' => ['status' => $data['status'], 'payment_status' => $data['payment_status']],
            'note' => $data['note'] ?? null,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order updated.');
    }
}