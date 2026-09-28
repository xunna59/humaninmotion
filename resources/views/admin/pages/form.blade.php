@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $page->exists ? 'Edit: ' . $page->title : 'New page' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.pages.index') }}" class="underline underline-offset-4">Pages</a></p>
    </header>

    <form method="POST" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" class="max-w-3xl space-y-6">
        @csrf
        @if ($page->exists)
            @method('PUT')
        @endif

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Details</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="title">Title</label>
                    <input class="input" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                </div>
                <div>
                    <label class="label" for="slug">Slug</label>
                    <input class="input" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="auto-generated">
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" class="checkbox" @checked(old('is_active', $page->is_active ?? true))>
                Active (visible on the storefront)
            </label>
            <div>
                <label class="label" for="content">Content</label>
                <textarea class="input font-mono text-xs leading-relaxed" id="content" name="content" rows="14">{{ old('content', $page->content) }}</textarea>
                <p class="mt-1 text-xs text-graphite">HTML is allowed — headings, paragraphs and lists are styled by the editorial prose theme.</p>
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">SEO</h2>
            <div>
                <label class="label" for="meta_title">Meta title</label>
                <input class="input" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
            </div>
            <div>
                <label class="label" for="meta_description">Meta description</label>
                <textarea class="input" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-primary">{{ $page->exists ? 'Save changes' : 'Create page' }}</button>
            <a href="{{ route('admin.pages.index') }}" class="btn-bone">Cancel</a>
        </div>
    </form>
@endsection