<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::query()->withCount('usages')->orderByDesc('created_at')->get(),
            'title' => 'Coupons',
        ]);
    }

    public function create(): View
    {
        return view('admin.coupons.form', [
            'coupon' => new Coupon(['is_active' => true]),
            'products' => Product::query()->active()->orderBy('name')->get(['id', 'name']),
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'collections' => Collection::query()->orderBy('name')->get(['id', 'name']),
            'title' => 'New coupon',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['code'] = strtoupper(Str::slug($request->input('code'), ''));

        $coupon = Coupon::create($data);
        AuditLog::record('admin.coupon.created', $coupon, ['code' => $coupon->code]);

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon created.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.form', [
            'coupon' => $coupon,
            'products' => Product::query()->active()->orderBy('name')->get(['id', 'name']),
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'collections' => Collection::query()->orderBy('name')->get(['id', 'name']),
            'title' => 'Edit: ' . $coupon->code,
        ]);
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $this->validated($request, $coupon);
        $data['code'] = strtoupper(Str::slug($request->input('code'), ''));

        $coupon->update($data);
        AuditLog::record('admin.coupon.updated', $coupon, ['code' => $coupon->code]);

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon saved.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        if ($coupon->usages()->exists()) {
            $coupon->update(['is_active' => false]);

            return back()->with('status', 'Coupon has usage history, so it was deactivated instead of deleted.');
        }

        $coupon->delete();
        AuditLog::record('admin.coupon.deleted', null, ['code' => $coupon->code]);

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon deleted.');
    }

    private function validated(Request $request, ?Coupon $coupon = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code' . ($coupon ? ',' . $coupon->id : '')],
            'type' => ['required', 'in:percentage,fixed,free_shipping'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'min_spend' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'applies_to' => ['nullable', 'in:all,products,categories,collections'],
            'applies_ids' => ['nullable', 'array'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_customer_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}