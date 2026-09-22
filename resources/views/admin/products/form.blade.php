@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">{{ $product->exists ? 'Edit: ' . $product->name : 'New product' }}</h1>
            <p class="text-graphite mt-1">
                <a href="{{ route('admin.products.index') }}" class="underline underline-offset-4">Products</a>
                @if ($product->exists)
                    · <a href="{{ route('product.show', $product) }}" target="_blank" class="underline underline-offset-4">View on store</a>
                @endif
            </p>
        </div>
        <div class="flex gap-2">
            @if ($product->exists)
                <form method="POST" action="{{ route('admin.products.duplicate', $product) }}">
                    @csrf
                    <button class="btn-bone btn-sm">Duplicate</button>
                </form>
            @endif
            <button type="submit" form="product-form" class="btn-primary btn-sm">
                {{ $product->exists ? 'Save changes' : 'Create product' }}
            </button>
        </div>
    </header>

    <form id="product-form" method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Details</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="label" for="name">Name</label>
                            <input class="input" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div>
                            <label class="label" for="slug">Slug</label>
                            <input class="input" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="auto-generated">
                        </div>
                        <div>
                            <label class="label" for="sku">SKU</label>
                            <input class="input" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                        </div>
                        <div>
                            <label class="label" for="brand">Brand</label>
                            <input class="input" id="brand" name="brand" value="{{ old('brand', $product->brand) }}" placeholder="Human In Motion">
                        </div>
                        <div>
                            <label class="label" for="category_id">Category</label>
                            <select class="select" id="category_id" name="category_id">
                                <option value="">None</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label" for="short_description">Short description</label>
                            <textarea class="input" id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label" for="description">Description</label>
                            <textarea class="input" id="description" name="description" rows="8">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Pricing</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label" for="price">Price (£)</label>
                            <input class="input" type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $product->price) }}">
                        </div>
                        <div>
                            <label class="label" for="compare_price">Compare-at price (£)</label>
                            <input class="input" type="number" step="0.01" min="0" id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}">
                        </div>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Variants (size / colour / stock)</h2>
                        <button type="button" id="add-variant" class="btn-bone btn-sm">Add variant</button>
                    </div>
                    <p class="text-xs text-graphite">Variants with both size and colour empty are ignored. Blank size means one-size.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="variants-table">
                            <thead>
                                <tr class="text-left text-[11px] uppercase tracking-[--tracking-label] text-graphite border-b border-ink/15">
                                    <th class="py-2 pr-2">Size</th>
                                    <th class="py-2 pr-2">Colour</th>
                                    <th class="py-2 pr-2">SKU</th>
                                    <th class="py-2 pr-2">Price (£)</th>
                                    <th class="py-2 pr-2">Compare (£)</th>
                                    <th class="py-2 pr-2">Stock</th>
                                    <th class="py-2 pr-2">Low</th>
                                    <th class="py-2 pr-2">Active</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->variants as $variant)
                                    @include('admin.products._variant-row', ['row' => $variant->toArray(), 'prefix' => 'variants[' . $variant->id . ']', 'id' => $variant->id])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Images</h2>
                        <button type="button" id="add-image" class="btn-bone btn-sm">Add image</button>
                    </div>
                    <p class="text-xs text-graphite">Demo uses placeholder SVG URLs, e.g. <code>/placeholder/product-box-fit-logo-tee-1.svg</code>. Position 1 = primary.</p>
                    <div id="images-list" class="space-y-3">
                        @foreach ($product->images as $image)
                            @include('admin.products._image-row', ['row' => $image->toArray(), 'id' => $image->id])
                        @endforeach
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Collections</h2>
                    <div class="grid sm:grid-cols-3 gap-3">
                        @forelse ($collections as $collection)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="collections[]" value="{{ $collection->id }}"
                                       class="checkbox"
                                       @checked(in_array($collection->id, old('collections', $product->collections->pluck('id')->all())))>
                                {{ $collection->name }}
                            </label>
                        @empty
                            <p class="text-graphite text-sm">No collections yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Status</h2>
                    <div>
                        <label class="label" for="status">Status</label>
                        <select class="select" id="status" name="status">
                            @foreach (['active' => 'Active', 'draft' => 'Draft', 'archived' => 'Archived'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $product->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach (['is_featured' => 'Featured', 'is_bestseller' => 'Best seller', 'is_new' => 'New in', 'is_limited' => 'Limited', 'is_exclusive' => 'Exclusive'] as $field => $label)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="{{ $field }}" value="1" class="checkbox" @checked((bool) old($field, $product->$field))>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Product attributes</h2>
                    <div>
                        <label class="label" for="fit">Fit</label>
                        <input class="input" id="fit" name="fit" value="{{ old('fit', $product->fit) }}">
                    </div>
                    <div>
                        <label class="label" for="material">Material</label>
                        <input class="input" id="material" name="material" value="{{ old('material', $product->material) }}">
                    </div>
                    <div>
                        <label class="label" for="style">Style</label>
                        <input class="input" id="style" name="style" value="{{ old('style', $product->style) }}">
                    </div>
                    <div>
                        <label class="label" for="care_instructions">Care instructions</label>
                        <textarea class="input" id="care_instructions" name="care_instructions" rows="3">{{ old('care_instructions', $product->care_instructions) }}</textarea>
                    </div>
                    <div>
                        <label class="label" for="tags">Tags (comma separated)</label>
                        <input class="input" id="tags" name="tags" value="{{ old('tags', is_array($product->tags) ? implode(', ', $product->tags) : $product->tags) }}">
                    </div>
                    <div>
                        <label class="label" for="video_url">Video URL</label>
                        <input class="input" type="url" id="video_url" name="video_url" value="{{ old('video_url', $product->video_url) }}">
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">SEO</h2>
                    <div>
                        <label class="label" for="meta_title">Meta title</label>
                        <input class="input" id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
                    </div>
                    <div>
                        <label class="label" for="meta_description">Meta description</label>
                        <textarea class="input" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                    <div>
                        <label class="label" for="canonical_url">Canonical URL</label>
                        <input class="input" type="url" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $product->canonical_url) }}">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="variant-row-template">
        @include('admin.products._variant-row', ['row' => ['id' => '', 'sku' => '', 'size' => '', 'colour' => '', 'price' => '', 'compare_price' => '', 'stock' => 0, 'low_stock_threshold' => 3, 'is_active' => true], 'prefix' => 'variants[new_0]', 'id' => ''])
    </template>

    <template id="image-row-template">
        @include('admin.products._image-row', ['row' => ['id' => '', 'path' => '', 'alt_text' => '', 'position' => ''], 'id' => ''])
    </template>

    <script>
        let variantSeq = 0;
        let imageSeq = 0;

        document.getElementById('add-variant')?.addEventListener('click', () => {
            variantSeq++;
            const tpl = document.getElementById('variant-row-template').innerHTML
                .replaceAll('variants[new_0]', 'variants[new_' + variantSeq + ']');
            document.querySelector('#variants-table tbody').insertAdjacentHTML('beforeend', tpl);
        });

        document.getElementById('add-image')?.addEventListener('click', () => {
            imageSeq++;
            const tpl = document.getElementById('image-row-template').innerHTML
                .replaceAll('images[new_0]', 'images[new_' + imageSeq + ']');
            document.getElementById('images-list').insertAdjacentHTML('beforeend', tpl);
        });

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-row');
            if (btn) btn.closest('tr, .image-row').remove();
        });
    </script>
@endsection