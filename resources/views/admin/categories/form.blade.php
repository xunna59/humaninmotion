@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $category->exists ? 'Edit: ' . $category->name : 'New category' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.categories.index') }}" class="underline underline-offset-4">Categories</a></p>
    </header>

    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="max-w-2xl">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="card p-5 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="name">Name</label>
                    <input class="input" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                </div>
                <div>
                    <label class="label" for="slug">Slug</label>
                    <input class="input" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="auto-generated">
                </div>
                <div>
                    <label class="label" for="parent_id">Parent category</label>
                    <select class="select" id="parent_id" name="parent_id">
                        <option value="">None (top level)</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="position">Position</label>
                    <input class="input" type="number" id="position" name="position" value="{{ old('position', $category->position ?? 0) }}">
                </div>
            </div>

            <div>
                <label class="label" for="description">Description</label>
                <textarea class="input" id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="image">Image URL</label>
                    <input class="input" id="image" name="image" value="{{ old('image', $category->image) }}">
                </div>
                <div>
                    <label class="label" for="banner">Banner URL</label>
                    <input class="input" id="banner" name="banner" value="{{ old('banner', $category->banner) }}">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="meta_title">Meta title</label>
                    <input class="input" id="meta_title" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}">
                </div>
                <div>
                    <label class="label" for="meta_description">Meta description</label>
                    <input class="input" id="meta_description" name="meta_description" value="{{ old('meta_description', $category->meta_description) }}">
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" class="checkbox" @checked(old('is_active', $category->is_active ?? true))>
                    Active
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="show_in_nav" value="1" class="checkbox" @checked(old('show_in_nav', $category->show_in_nav ?? true))>
                    Show in navigation
                </label>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button class="btn-primary">{{ $category->exists ? 'Save changes' : 'Create category' }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-bone">Cancel</a>
        </div>
    </form>
@endsection