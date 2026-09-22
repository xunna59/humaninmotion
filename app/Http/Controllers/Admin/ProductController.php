<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->with(['category', 'variants']);

        if ($term = trim($request->string('q')->toString())) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->boolean('low_stock')) {
            $query->whereHas('variants', function ($q) {
                $q->whereColumn('stock', '<=', 'low_stock_threshold');
            });
        }

        return view('admin.products.index', [
            'products' => $query->orderByDesc('updated_at')->paginate(25)->withQueryString(),
            'categories' => Category::query()->orderBy('position')->get(),
            'title' => 'Products',
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['status' => Product::STATUS_ACTIVE]),
            'categories' => Category::query()->orderBy('position')->get(),
            'collections' => Collection::query()->orderBy('position')->get(),
            'title' => 'New product',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data = $this->fillData($data);

        $product = Product::create($data);
        $this->syncCollections($product, $request);
        $this->syncVariants($product, $request);
        $this->syncImages($product, $request);

        AuditLog::record('admin.product.created', $product, ['name' => $product->name]);

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product created.');
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product->load(['variants', 'images', 'collections', 'category']),
            'categories' => Category::query()->orderBy('position')->get(),
            'collections' => Collection::query()->orderBy('position')->get(),
            'title' => 'Edit: ' . $product->name,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product);
        $data = $this->fillData($data);

        $product->update($data);
        $this->syncCollections($product, $request);
        $this->syncVariants($product, $request);
        $this->syncImages($product, $request);

        AuditLog::record('admin.product.updated', $product, ['name' => $product->name]);

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product saved.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->update(['status' => Product::STATUS_ARCHIVED]);
        AuditLog::record('admin.product.archived', $product, ['name' => $product->name]);

        return back()->with('status', 'Product archived.');
    }

    public function updateStatus(Request $request, Product $product): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:scheduled,active,draft,archived']]);

        $product->update([
            'status' => $request->input('status'),
            'published_at' => $request->input('status') === Product::STATUS_ACTIVE
                ? ($product->published_at ?: now())
                : $product->published_at,
        ]);

        AuditLog::record('admin.product.status', $product, ['status' => $request->input('status')]);

        return back()->with('status', 'Status updated to ' . ucfirst($request->input('status')) . '.');
    }

    public function duplicate(Product $product): RedirectResponse
    {
        $copy = $product->replicate();
        $copy->name = $product->name . ' (Copy)';
        $copy->slug = Str::slug($copy->name) . '-' . Str::lower(Str::random(4));
        $copy->status = Product::STATUS_DRAFT;
        $copy->published_at = null;
        $copy->views = 0;
        $copy->save();

        foreach ($product->variants as $variant) {
            $copy->variants()->create($variant->only([
                'sku', 'size', 'colour', 'price', 'compare_price', 'stock', 'low_stock_threshold', 'barcode', 'is_active', 'position',
            ]));
        }

        foreach ($product->images as $image) {
            $copy->images()->create($image->only(['path', 'alt_text', 'position']));
        }

        $copy->collections()->sync($product->collections->pluck('id'));

        AuditLog::record('admin.product.duplicated', $copy, ['from' => $product->name]);

        return redirect()->route('admin.products.edit', $copy)->with('status', 'Product duplicated.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:products,slug' . ($product ? ',' . $product->id : '')],
            'sku' => ['nullable', 'string', 'max:64'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:120'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,active,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'is_bestseller' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'is_limited' => ['nullable', 'boolean'],
            'is_exclusive' => ['nullable', 'boolean'],
            'fit' => ['nullable', 'string', 'max:255'],
            'material' => ['nullable', 'string', 'max:255'],
            'style' => ['nullable', 'string', 'max:255'],
            'care_instructions' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'collections' => ['nullable', 'array'],
            'collections.*' => ['integer', 'exists:collections,id'],
            'variants' => ['nullable', 'array'],
            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.sku' => ['nullable', 'string', 'max:64'],
            'variants.*.size' => ['nullable', 'string', 'max:24'],
            'variants.*.colour' => ['nullable', 'string', 'max:64'],
            'variants.*.price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.compare_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
            'variants.*.low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*.id' => ['nullable', 'integer'],
            'images.*.path' => ['nullable', 'string', 'max:500'],
            'images.*.alt_text' => ['nullable', 'string', 'max:255'],
            'images.*.position' => ['nullable', 'integer'],
        ]);
    }

    private function fillData(array $data): array
    {
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['name']);
        $data['is_featured'] = isset($data['is_featured']);
        $data['is_bestseller'] = isset($data['is_bestseller']);
        $data['is_new'] = isset($data['is_new']);
        $data['is_limited'] = isset($data['is_limited']);
        $data['is_exclusive'] = isset($data['is_exclusive']);
        $data['tags'] = ($data['tags'] ?? '')
            ? collect(str_getcsv($data['tags']))->map(fn ($t) => trim($t))->filter()->values()->all()
            : [];

        return $data;
    }

    private function syncCollections(Product $product, Request $request): void
    {
        $product->collections()->sync($request->input('collections', []));
    }

    private function syncVariants(Product $product, Request $request): void
    {
        $submitted = collect($request->input('variants', []))
            ->filter(fn ($row) => ($row['size'] ?? '') !== '' || ($row['colour'] ?? '') !== '')
            ->values();

        $keptIds = [];

        foreach ($submitted as $i => $row) {
            $row['position'] = $i;
            $row['is_active'] = (bool) ($row['is_active'] ?? false);
            $row['stock'] = (int) ($row['stock'] ?? 0);
            $row['low_stock_threshold'] = (int) ($row['low_stock_threshold'] ?? 3);

            if (! empty($row['id'])) {
                $product->variants()->whereKey($row['id'])->update($row);
                $keptIds[] = (int) $row['id'];
            } else {
                $keptIds[] = $product->variants()->create($row)->id;
            }
        }

        $orphans = count($keptIds)
            ? $product->variants()->whereNotIn('id', $keptIds)->get()
            : collect();

        foreach ($orphans as $orphan) {
            $referenced = \DB::table('order_items')->where('variant_id', $orphan->id)->exists()
                || \DB::table('cart_items')->where('variant_id', $orphan->id)->exists();
            if ($referenced) {
                $orphan->update(['is_active' => false]);
            } else {
                $orphan->delete();
            }
        }
    }

    private function syncImages(Product $product, Request $request): void
    {
        $submitted = collect($request->input('images', []))
            ->filter(fn ($row) => trim((string) $row['path']) !== '')
            ->values();

        $keptIds = [];

        foreach ($submitted as $i => $row) {
            $row['position'] = $row['position'] ?: ($i + 1);

            if (! empty($row['id'])) {
                $product->images()->whereKey($row['id'])->update($row);
                $keptIds[] = (int) $row['id'];
            } else {
                $keptIds[] = $product->images()->create($row)->id;
            }
        }

        if (count($keptIds)) {
            $product->images()->whereNotIn('id', $keptIds)->delete();
        }
    }
}