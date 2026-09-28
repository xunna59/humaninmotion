@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $article->exists ? 'Edit: ' . $article->title : 'New article' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.journal.index') }}" class="underline underline-offset-4">Journal</a></p>
    </header>

    @php
        $gallery = old('gallery_keep', $article->gallery ?? []);
        $coverUrl = $article instanceof App\Models\JournalArticle && $article->exists ? $article->coverImageUrl() : null;
    @endphp

    <form method="POST" action="{{ $article->exists ? route('admin.journal.update', $article) : route('admin.journal.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($article->exists)
            @method('PUT')
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6 min-w-0">
                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Details</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label" for="title">Title</label>
                            <input class="input" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                        </div>
                        <div>
                            <label class="label" for="slug">Slug</label>
                            <input class="input" id="slug" name="slug" value="{{ old('slug', $article->slug) }}" placeholder="auto-generated">
                        </div>
                        <div>
                            <label class="label" for="category">Category</label>
                            <input class="input" id="category" name="category" value="{{ old('category', $article->category) }}" placeholder="e.g. Styling Guides">
                        </div>
                        <div>
                            <label class="label" for="author">Author</label>
                            <input class="input" id="author" name="author" value="{{ old('author', $article->author) }}">
                        </div>
                    </div>
                    <div>
                        <label class="label" for="excerpt">Excerpt</label>
                        <textarea class="input" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Content</h2>
                    <label class="label" for="content">Body</label>
                    <textarea class="input font-mono text-xs leading-relaxed" id="content" name="content" rows="14">{{ old('content', $article->content) }}</textarea>
                    <p class="text-xs text-graphite">HTML is allowed — headings, paragraphs and lists are styled by the editorial prose theme.</p>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">SEO</h2>
                    <div>
                        <label class="label" for="meta_title">Meta title</label>
                        <input class="input" id="meta_title" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}">
                    </div>
                    <div>
                        <label class="label" for="meta_description">Meta description</label>
                        <textarea class="input" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $article->meta_description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Publishing</h2>
                    <div>
                        <label class="label" for="status">Status</label>
                        <select class="select" id="status" name="status">
                            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $article->status ?? 'draft') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label" for="published_at">Publish date</label>
                        <input class="input" type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}">
                        <p class="mt-1 text-xs text-graphite">Leave empty to publish immediately.</p>
                    </div>
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Cover image</h2>
                    <div>
                        <label class="label" for="cover_image">Upload cover</label>
                        <input class="input" type="file" id="cover_image" name="cover_image" accept="image/png,image/jpeg,image/webp,image/svg+xml" data-cover-input>
                        <p class="mt-1 text-xs text-graphite">PNG, JPG, WebP or SVG up to 4MB. Replaces any existing cover.</p>
                    </div>
                    @if ($coverUrl)
                        <div data-cover-preview>
                            <img src="{{ $coverUrl }}" alt="Cover preview" class="w-full aspect-[16/9] object-cover border border-ink/10 bg-warmgray" data-cover-preview-img>
                            <label class="flex items-center gap-2 text-sm mt-2">
                                <input type="checkbox" name="remove_cover" value="1" class="checkbox" @checked(old('remove_cover'))>
                                Remove cover and use placeholder
                            </label>
                        </div>
                    @else
                        <div data-cover-preview class="hidden">
                            <img src="" alt="Cover preview" class="w-full aspect-[16/9] object-cover border border-ink/10 bg-warmgray" data-cover-preview-img>
                        </div>
                    @endif
                </div>

                <div class="card p-5 space-y-4">
                    <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Gallery</h2>
                    <p class="text-xs text-graphite">Existing images show below. Remove any with the ×, then upload new ones (PNG, JPG, WebP or SVG, up to 4MB each).</p>
                    @if (count($gallery) > 0)
                        <div id="gallery-existing" class="grid grid-cols-2 gap-3">
                            @foreach ($gallery as $path)
                                @php $gallerySrc = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/']) ? $path : '/storage/' . ltrim($path, '/'); @endphp
                                <div class="gallery-item relative border border-ink/10 bg-warmgray" data-gallery-item>
                                    <img src="{{ $gallerySrc }}" alt="" class="w-full aspect-[4/3] object-cover">
                                    <input type="hidden" name="gallery_keep[]" value="{{ $path }}">
                                    <button type="button" class="remove-gallery absolute top-1 right-1 w-6 h-6 text-sm font-semibold bg-ink text-bone hover:bg-sale" aria-label="Remove image">×</button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div>
                        <label class="label" for="gallery_new">Upload images</label>
                        <input class="input" type="file" id="gallery_new" name="gallery_new[]" accept="image/png,image/jpeg,image/webp,image/svg+xml" multiple>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="btn-primary">{{ $article->exists ? 'Save changes' : 'Create article' }}</button>
            <a href="{{ route('admin.journal.index') }}" class="btn-bone">Cancel</a>
        </div>
    </form>

    <script>
        const coverInput = document.querySelector('[data-cover-input]');
        if (coverInput) {
            coverInput.addEventListener('change', () => {
                const file = coverInput.files[0];
                if (!file) {
                    return;
                }
                const preview = document.querySelector('[data-cover-preview]');
                const img = document.querySelector('[data-cover-preview-img]');
                if (preview && img) {
                    preview.classList.remove('hidden');
                    img.src = URL.createObjectURL(file);
                }
            });
        }

        document.getElementById('gallery-existing')?.addEventListener('click', (e) => {
            const item = e.target.closest('[data-gallery-item]');
            if (e.target.closest('.remove-gallery') && item) {
                item.remove();
            }
        });
    </script>
@endsection