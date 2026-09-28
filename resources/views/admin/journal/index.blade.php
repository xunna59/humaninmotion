@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
        <div>
            <h1 class="display-campaign text-4xl">Journal</h1>
            <p class="text-graphite mt-1">{{ $articles->count() }} articles</p>
        </div>
        <a href="{{ route('admin.journal.create') }}" class="btn-primary btn-sm">New article</a>
    </header>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Published</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($articles as $article)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if ($article->coverImageUrl())
                                    <img src="{{ $article->coverImageUrl() }}" alt="" class="w-12 h-9 object-cover border border-ink/10 bg-warmgray">
                                @endif
                                <span class="font-semibold">{{ $article->title }}</span>
                            </div>
                        </td>
                        <td class="text-graphite text-xs">{{ $article->category ?: '—' }}</td>
                        <td class="text-xs text-graphite">{{ $article->author ?: '—' }}</td>
                        <td class="text-xs text-graphite">{{ $article->published_at?->toDateString() ?: '—' }}</td>
                        <td>
                            <span class="badge {{ $article->isPublished() ? 'badge-brass' : 'badge-dark' }}">{{ $article->isPublished() ? 'Published' : 'Draft' }}</span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('journal.show', $article->slug) }}" target="_blank" class="text-xs underline underline-offset-4 hover:text-brass">View</a>
                                <a href="{{ route('admin.journal.edit', $article) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.journal.destroy', $article) }}"
                                      onsubmit="return confirm('Delete {{ $article->title }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-graphite py-10">No articles yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection