<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        return view('admin.promotions.index', [
            'promotions' => Promotion::query()->orderByDesc('created_at')->get(),
            'title' => 'Promotions',
        ]);
    }

    public function create(): View
    {
        return view('admin.promotions.form', [
            'promotion' => new Promotion(['is_active' => true, 'type' => Promotion::TYPE_PERCENTAGE, 'applies_to' => Promotion::APPLIES_ALL]),
            'products' => Product::query()->active()->orderBy('name')->get(['id', 'name']),
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'collections' => Collection::query()->orderBy('name')->get(['id', 'name']),
            'title' => 'New promotion',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $promotion = Promotion::create($data);
        AuditLog::record('admin.promotion.created', $promotion, ['name' => $promotion->name]);

        return redirect()->route('admin.promotions.index')->with('status', 'Promotion created.');
    }

    public function edit(Promotion $promotion): View
    {
        return view('admin.promotions.form', [
            'promotion' => $promotion,
            'products' => Product::query()->active()->orderBy('name')->get(['id', 'name']),
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'collections' => Collection::query()->orderBy('name')->get(['id', 'name']),
            'title' => 'Edit: ' . $promotion->name,
        ]);
    }

    public function update(Request $request, Promotion $promotion): RedirectResponse
    {
        $data = $this->validated($request);

        $promotion->update($data);
        AuditLog::record('admin.promotion.updated', $promotion, ['name' => $promotion->name]);

        return redirect()->route('admin.promotions.index')->with('status', 'Promotion saved.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();
        AuditLog::record('admin.promotion.deleted', null, ['name' => $promotion->name]);

        return redirect()->route('admin.promotions.index')->with('status', 'Promotion deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'applies_to' => ['required', 'in:all,products,categories,collections'],
            'applies_ids' => ['nullable', 'array'],
            'applies_ids.*' => ['integer'],
            'badge' => ['nullable', 'string', 'max:120'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}