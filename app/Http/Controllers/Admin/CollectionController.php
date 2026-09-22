<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        return view('admin.collections.index', [
            'collections' => Collection::query()->withCount('products')->orderBy('position')->get(),
            'title' => 'Collections',
        ]);
    }

    public function create(): View
    {
        return view('admin.collections.form', [
            'collection' => new Collection(['is_active' => true, 'position' => Collection::query()->max('position') + 1]),
            'products' => Product::query()->active()->orderBy('name')->get(['id', 'name', 'slug']),
            'title' => 'New collection',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $collection = Collection::create($data);
        $collection->products()->sync($request->input('products', []));
        AuditLog::record('admin.collection.created', $collection, ['name' => $collection->name]);

        return redirect()->route('admin.collections.index')->with('status', 'Collection created.');
    }

    public function edit(Collection $collection): View
    {
        return view('admin.collections.form', [
            'collection' => $collection->load('products:id,name,slug'),
            'products' => Product::query()->active()->orderBy('name')->get(['id', 'name', 'slug']),
            'title' => 'Edit: ' . $collection->name,
        ]);
    }

    public function update(Request $request, Collection $collection): RedirectResponse
    {
        $data = $this->validated($request, $collection);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $collection->update($data);
        $collection->products()->sync($request->input('products', []));
        AuditLog::record('admin.collection.updated', $collection, ['name' => $collection->name]);

        return redirect()->route('admin.collections.index')->with('status', 'Collection saved.');
    }

    public function destroy(Collection $collection): RedirectResponse
    {
        $collection->products()->detach();
        $collection->delete();
        AuditLog::record('admin.collection.deleted', null, ['name' => $collection->name]);

        return redirect()->route('admin.collections.index')->with('status', 'Collection deleted.');
    }

    private function validated(Request $request, ?Collection $collection = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:collections,slug' . ($collection ? ',' . $collection->id : '')],
            'description' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'mobile_hero_image' => ['nullable', 'string', 'max:500'],
            'campaign_video' => ['nullable', 'string', 'max:500'],
            'promotional_text' => ['nullable', 'string'],
            'cta_text' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'position' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'products' => ['nullable', 'array'],
            'products.*' => ['integer', 'exists:products,id'],
        ]);
    }
}