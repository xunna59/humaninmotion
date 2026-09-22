<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->withCount(['products', 'children'])->orderBy('position')->get(),
            'title' => 'Categories',
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(['is_active' => true, 'show_in_nav' => true]),
            'parents' => Category::query()->whereNull('parent_id')->orderBy('position')->get(),
            'title' => 'New category',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $category = Category::create($data);
        AuditLog::record('admin.category.created', $category, ['name' => $category->name]);

        return redirect()->route('admin.categories.index')->with('status', 'Category created.');
    }

    public function edit(Category $category): View
    {
        $category->load(['products' => fn ($q) => $q->limit(10)]);

        return view('admin.categories.form', [
            'category' => $category,
            'parents' => Category::query()->whereNull('parent_id')->whereKeyNot($category->id)->orderBy('position')->get(),
            'title' => 'Edit: ' . $category->name,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request, $category);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $category->update($data);
        AuditLog::record('admin.category.updated', $category, ['name' => $category->name]);

        return redirect()->route('admin.categories.index')->with('status', 'Category saved.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists() || $category->children()->exists()) {
            return back()->withErrors(['category' => 'Category has products or sub-categories. Reassign them first.']);
        }

        $category->delete();
        AuditLog::record('admin.category.deleted', null, ['name' => $category->name]);

        return redirect()->route('admin.categories.index')->with('status', 'Category deleted.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        foreach ((array) $request->input('order', []) as $position => $id) {
            Category::query()->whereKey($id)->update(['position' => $position]);
        }

        AuditLog::record('admin.category.reordered');

        return back()->with('status', 'Category order saved.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:categories,slug' . ($category ? ',' . $category->id : '')],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'banner' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'position' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'show_in_nav' => ['nullable', 'boolean'],
        ]);
    }
}