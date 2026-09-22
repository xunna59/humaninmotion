@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $collection->exists ? 'Edit: ' . $collection->name : 'New collection' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.collections.index') }}" class="underline underline-offset-4">Collections</a></p>
    </header>

    <form method="POST" action="{{ $collection->exists ? route('admin.collections.update', $collection) : route('admin.collections.store') }}">
        @csrf
        @if ($collection->exists)
            @method('PUT')
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Details</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label" for="name">Name</label>
                            <input class="input" id="name" name="name" value="{{ old('name', $collection->name) }}" required>
                        </div>
                        <div>
                            <label class="label" for="slug">Slug</label>
                            <input class="input" id="slug" name="slug" value="{{ old('slug', $collection->slug) }}" placeholder="auto-generated">
                        </div>
                        <div>
                            <label class="label" for="starts_at">Starts</label>
                            <input class="input" type="date" id="starts_at" name="starts_at"
                                   value="{{ old('starts_at', $collection->starts_at?->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="label" for="ends_at">Ends</label>
                            <input class="input" type="date" id="ends_at" name="ends_at"
                                   value="{{ old('ends_at', $collection->ends_at?->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="label" for="position">Position</label>
                            <input class="input" type="number" id="position" name="position" value="{{ old('position', $collection->position ?? 0) }}">
                        </div>
                        <div class="flex items-end gap-6 pb-2">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_active" value="1" class="checkbox" @checked(old('is_active', $collection->is_active ?? true))>
                                Active
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_featured" value="1" class="checkbox" @checked(old('is_featured', $collection->is_featured))>
                                Featured
                            </label>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label" for="description">Description</label>
                            <textarea class="input" id="description" name="description" rows="4">{{ old('description', $collection->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Media</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label" for="hero_image">Hero image URL</label>
                            <input class="input" id="hero_image" name="hero_image" value="{{ old('hero_image', $collection->hero_image) }}">
                        </div>
                        <div>
                            <label class="label" for="mobile_hero_image">Mobile hero URL</label>
                            <input class="input" id="mobile_hero_image" name="mobile_hero_image" value="{{ old('mobile_hero_image', $collection->mobile_hero_image) }}">
                        </div>
                        <div>
                            <label class="label" for="campaign_video">Campaign video URL</label>
                            <input class="input" id="campaign_video" name="campaign_video" value="{{ old('campaign_video', $collection->campaign_video) }}">
                        </div>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Promo &amp; CTA</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="label" for="promotional_text">Promotional text</label>
                            <textarea class="input" id="promotional_text" name="promotional_text" rows="3">{{ old('promotional_text', $collection->promotional_text) }}</textarea>
                        </div>
                        <div>
                            <label class="label" for="cta_text">CTA text</label>
                            <input class="input" id="cta_text" name="cta_text" value="{{ old('cta_text', $collection->cta_text) }}">
                        </div>
                        <div>
                            <label class="label" for="cta_url">CTA URL</label>
                            <input class="input" id="cta_url" name="cta_url" value="{{ old('cta_url', $collection->cta_url) }}">
                        </div>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">SEO</h2>
                    <div>
                        <label class="label" for="meta_title">Meta title</label>
                        <input class="input" id="meta_title" name="meta_title" value="{{ old('meta_title', $collection->meta_title) }}">
                    </div>
                    <div>
                        <label class="label" for="meta_description">Meta description</label>
                        <textarea class="input" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $collection->meta_description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-5">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Products in collection</h2>
                    <div class="max-h-96 overflow-y-auto space-y-2 pr-2">
                        @forelse ($products as $product)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="products[]" value="{{ $product->id }}"
                                       class="checkbox"
                                       @checked(in_array($product->id, old('products', $collection->products->pluck('id')->all())))>
                                {{ $product->name }}
                            </label>
                        @empty
                            <p class="text-graphite text-sm">No active products to attach.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button class="btn-primary">{{ $collection->exists ? 'Save changes' : 'Create collection' }}</button>
            <a href="{{ route('admin.collections.index') }}" class="btn-bone">Cancel</a>
        </div>
    </form>
@endsection