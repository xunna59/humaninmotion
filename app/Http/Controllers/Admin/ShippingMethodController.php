<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShippingMethodController extends Controller
{
    public function index(): View
    {
        return view('admin.shipping.index', [
            'methods' => ShippingMethod::query()->orderBy('sort_order')->get(),
            'title' => 'Shipping',
        ]);
    }

    public function create(): View
    {
        return view('admin.shipping.form', [
            'method' => new ShippingMethod(['is_active' => true, 'sort_order' => 0]),
            'countries' => Country::query()->orderBy('name')->get(),
            'title' => 'New shipping method',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $method = ShippingMethod::create($data);
        AuditLog::record('admin.shipping.created', $method, ['name' => $method->name]);

        return redirect()->route('admin.shipping.index')->with('status', 'Shipping method created.');
    }

    public function edit(ShippingMethod $method): View
    {
        return view('admin.shipping.form', [
            'method' => $method,
            'countries' => Country::query()->orderBy('name')->get(),
            'title' => 'Edit: '.$method->name,
        ]);
    }

    public function update(Request $request, ShippingMethod $method): RedirectResponse
    {
        $data = $this->validated($request, $method);

        $method->update($data);
        AuditLog::record('admin.shipping.updated', $method, ['name' => $method->name]);

        return redirect()->route('admin.shipping.index')->with('status', 'Shipping method saved.');
    }

    public function destroy(ShippingMethod $method): RedirectResponse
    {
        if ($method->is_active && ShippingMethod::query()->where('is_active', true)->count() <= 1) {
            return back()->withErrors(['method' => 'At least one active shipping method is required. Deactivate one only if another is active.']);
        }

        AuditLog::record('admin.shipping.deleted', null, ['name' => $method->name]);
        $method->delete();

        return redirect()->route('admin.shipping.index')->with('status', 'Shipping method deleted.');
    }

    private function validated(Request $request, ?ShippingMethod $method = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:60', 'regex:/^[a-z0-9]+(?:[_-][a-z0-9]+)*$/', 'unique:shipping_methods,code'.($method ? ','.$method->id : '')],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'estimate' => ['nullable', 'string', 'max:255'],
            'zones' => ['nullable', 'array'],
            'zones.*' => ['string', 'exists:countries,code'],
            'free_above' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['code'] ??= Str::snake($data['name']);
        $data['zones'] = array_values(array_unique($data['zones'] ?? []));
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = $request->boolean('is_active');
        $data['free_above'] = $data['free_above'] !== '' && $data['free_above'] !== null ? (float) $data['free_above'] : null;
        $data['estimate'] = $data['estimate'] !== '' ? $data['estimate'] : null;

        return $data;
    }
}
