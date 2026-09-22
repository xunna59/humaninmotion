@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $section->exists ? 'Edit homepage section' : 'New homepage section' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.home.index') }}" class="underline underline-offset-4">Homepage</a></p>
    </header>

    <form method="POST" action="{{ $section->exists ? route('admin.home.update', $section) : route('admin.home.store') }}" class="max-w-3xl">
        @csrf
        @if ($section->exists)
            @method('PUT')
        @endif

        <div class="card p-5 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="type">Section type</label>
                    <select class="select" id="type" name="type">
                        @foreach (['hero','image_banner','product_carousel','product_grid','collection_tiles','editorial','promotional_banner','category_grid','newsletter','custom'] as $type)
                            <option value="{{ $type }}" @selected(old('type', $section->type) === $type)>{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="sort_order">Sort order</label>
                    <input class="input" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $section->sort_order ?? 0) }}">
                </div>
                <div>
                    <label class="label" for="text_position">Text position</label>
                    <select class="select" id="text_position" name="text_position">
                        @foreach (['left' => 'Left', 'center' => 'Centre', 'right' => 'Right'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('text_position', $section->text_position) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="overlay_strength">Overlay strength (%)</label>
                    <input class="input" type="number" min="0" max="100" id="overlay_strength" name="overlay_strength" value="{{ old('overlay_strength', $section->overlay_strength ?? 40) }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="title">Title</label>
                    <input class="input" id="title" name="title" value="{{ old('title', $section->title) }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="description">Description</label>
                    <textarea class="input" id="description" name="description" rows="3">{{ old('description', $section->description) }}</textarea>
                </div>
                <div>
                    <label class="label" for="image_desktop">Desktop image URL</label>
                    <input class="input" id="image_desktop" name="image_desktop" value="{{ old('image_desktop', $section->image_desktop) }}">
                </div>
                <div>
                    <label class="label" for="image_mobile">Mobile image URL</label>
                    <input class="input" id="image_mobile" name="image_mobile" value="{{ old('image_mobile', $section->image_mobile) }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="video_url">Video URL</label>
                    <input class="input" id="video_url" name="video_url" value="{{ old('video_url', $section->video_url) }}">
                </div>
                <div>
                    <label class="label" for="button_text">Button text</label>
                    <input class="input" id="button_text" name="button_text" value="{{ old('button_text', $section->button_text) }}">
                </div>
                <div>
                    <label class="label" for="button_url">Button URL</label>
                    <input class="input" id="button_url" name="button_url" value="{{ old('button_url', $section->button_url) }}">
                </div>
                <div>
                    <label class="label" for="button_two_text">Secondary button text</label>
                    <input class="input" id="button_two_text" name="button_two_text" value="{{ old('button_two_text', $section->button_two_text) }}">
                </div>
                <div>
                    <label class="label" for="button_two_url">Secondary button URL</label>
                    <input class="input" id="button_two_url" name="button_two_url" value="{{ old('button_two_url', $section->button_two_url) }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="content">Content (JSON)</label>
                    <textarea class="input" id="content" name="content" rows="3" placeholder='{"ids": [1, 2, 3]}'>{{ old('content', is_array($section->content) ? json_encode($section->content) : $section->content) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" class="checkbox" @checked(old('is_active', $section->is_active ?? true))>
                    Active
                </label>
                <button class="btn-primary">{{ $section->exists ? 'Save changes' : 'Create section' }}</button>
            </div>
        </div>
    </form>
@endsection