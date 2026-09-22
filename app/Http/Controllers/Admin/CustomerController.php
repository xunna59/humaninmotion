<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->withCount('orders');

        if ($term = trim($request->string('q')->toString())) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        return view('admin.customers.index', [
            'customers' => $query->orderByDesc('created_at')->paginate(25)->withQueryString(),
            'title' => 'Customers',
        ]);
    }

    public function show(User $user): View
    {
        $user->load(['addresses', 'wishlist.items.product:id,name,slug']);
        $orders = $user->orders()->withCount('items')->orderByDesc('created_at')->get();

        return view('admin.customers.show', [
            'customer' => $user,
            'orders' => $orders,
            'lifetimeSpend' => (float) $orders->whereIn('payment_status', ['paid', 'refunded', 'partially_refunded'])->where('status', '!=', 'cancelled')->sum('total'),
            'totalOrders' => $orders->count(),
            'title' => 'Customer: ' . $user->name,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'is_active' => ['nullable', 'boolean'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $user->update(['is_active' => $data['is_active'] ?? $user->is_active]);
        AuditLog::record('admin.customer.updated', $user, $data);

        return back()->with('status', $data['is_active'] ?? true ? 'Account enabled.' : 'Account disabled.');
    }
}