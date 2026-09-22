@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Products</h1>
            <p class="text-graphite mt-1">{{ $products->total() }} products</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary btn-sm">New product</a>
    </header>

    <form method="GET" class="card p-4 mb-6 grid sm:grid-cols-4 gap-3">
        <div>
            <label class="label" for="q">Search</label>
            <input class="input" type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Name or SKU">
        </div>
        <div>
            <label class="label" for="status">Status</label>
            <select class="select" id="status" name="status">
                <option value="">All</option>
                @foreach (['active' => 'Active', 'draft' => 'Draft', 'archived' => 'Archived'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label" for="category_id">Category</label>
            <select class="select" id="category_id" name="category_id">
                <option value="">All</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-3">
            <label class="flex items-center gap-2 text-sm pb-3">
                <input type="checkbox" name="low_stock" value="1" class="checkbox" @checked(request()->boolean('low_stock'))>
                Low stock only
            </label>
            <button type="submit" class="btn-bone btn-sm">Filter</button>
            @if (request()->hasAny('q', 'status', 'category_id', 'low_stock'))
                <a href="{{ route('admin.products.index') }}" class="text-sm text-graphite pb-3 hover:text-ink">Clear</a>
            @endif
        </div>
    </form>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-center">Stock</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="font-semibold hover:text-brass">
                                {{ $product->name }}
                            </a>
                            @if ($product->is_featured || $product->is_bestseller || $product->is_new)
                                <div class="flex gap-1 mt-1">
                                    @if ($product->is_featured) <span class="badge badge-brass">Featured</span> @endif
                                    @if ($product->is_bestseller) <span class="badge badge-dark">Best seller</span> @endif
                                    @if ($product->is_new) <span class="badge badge-outline">New</span> @endif
                                </div>
                            @endif
                        </td>
                        <td class="text-graphite text-xs">{{ $product->sku ?: '—' }}</td>
                        <td class="text-graphite text-xs">{{ $product->category?->name ?: '—' }}</td>
                        <td class="text-right">
                            £{{ number_format((float) ($product->variants->min('price') ?: $product->price), 2) }}
                            @if ($product->isOnSale())
                                <span class="badge badge-sale ml-1">Sale</span>
                            @endif
                        </td>
                        <td class="text-center {{ $product->totalStock() === 0 ? 'text-sale font-semibold' : 'text-graphite' }}">
                            {{ number_format($product->totalStock()) }}
                        </td>
                        <td>
                            <span class="badge {{ match ($product->status) { 'active' => 'badge-brass', 'draft' => 'badge-bone', default => 'badge-dark' } }}">
                                {{ $product->status }}
                            </span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('product.show', $product) }}" target="_blank" class="text-xs underline underline-offset-4 hover:text-brass">View</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.products.duplicate', $product) }}">
                                    @csrf
                                    <button class="text-xs underline underline-offset-4 text-graphite hover:text-ink">Copy</button>
                                </form>
                                @if ($product->status !== 'archived')
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          onsubmit="return confirm('Archive {{ $product->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Archive</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-10">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection