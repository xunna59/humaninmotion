<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totals = Order::query()
            ->whereDate('placed_at', '<=', now())
            ->where('status', '!=', Order::STATUS_CANCELLED)
            ->selectRaw(
                'count(*) as order_count,
                 coalesce(sum(case when payment_status in ("paid","refunded","partially_refunded") then total else 0 end), 0) as revenue
                 '
            )
            ->first();

        $revenue = (float) $totals->revenue;
        $orderCount = (int) $totals->order_count;
        $customerEmails = User::query()->where('role', 'customer')->pluck('email')->flip();
        $guestEmails = DB::table('orders')
            ->distinct()
            ->pluck('customer_email')
            ->reject(fn ($email) => $customerEmails->has($email))
            ->count();
        $customerCount = $customerEmails->count() + $guestEmails;
        $aov = $orderCount > 0 ? $revenue / $orderCount : 0.0;

        $paidOrders = Order::query()
            ->where('payment_status', 'paid')
            ->where('status', '!=', Order::STATUS_CANCELLED);

        $productsSold = (int) DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.quantity');

        $lowStock = ProductVariant::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->with('product:id,name,slug,status')
            ->orderBy('stock')
            ->limit(8)
            ->get();

        $recentOrders = Order::query()
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $recentCustomers = User::query()
            ->whereNotNull('email_verified_at')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $sales = DB::table('orders')
            ->where('payment_status', 'paid')
            ->whereBetween('placed_at', [now()->subDays(13)->startOfDay(), now()->endOfDay()])
            ->selectRaw('date(placed_at) as day, sum(total) as amount')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('amount', 'day')
            ->map(fn ($v) => (float) $v);

        $days = collect();
        for ($i = 13; $i >= 0; $i--) {
            $key = now()->subDays($i)->toDateString();
            $days[$key] = $sales[$key] ?? 0.0;
        }
        $maxDay = max(1.0, (float) $days->max());

        return view('admin.dashboard.index', [
            'revenue' => $revenue,
            'orderCount' => $orderCount,
            'customerCount' => $customerCount,
            'aov' => $aov,
            'productsSold' => $productsSold,
            'lowStock' => $lowStock,
            'recentOrders' => $recentOrders,
            'recentCustomers' => $recentCustomers,
            'sales' => $days,
            'maxDay' => $maxDay,
            'title' => 'Dashboard',
        ]);
    }
}