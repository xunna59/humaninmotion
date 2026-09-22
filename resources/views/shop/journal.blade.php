@extends('layouts.site')

@section('title', $title)

@section('content')
    <header class="bg-ink text-bone">
        <div class="container-site py-16 lg:py-20">
            <p class="eyebrow text-brass mb-3">THE JOURNAL</p>
            <h1 class="display-campaign text-5xl lg:text-7xl">IN THE MOMENT</h1>
        </div>
    </header>

    <div class="container-site py-10 lg:py-14">
        @if ($articles->isEmpty())
            <p class="text-sm text-graphite text-center py-16">Stories coming soon.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
                @foreach ($articles as $article)
                    <article>
                        <a href="{{ route('journal.show', $article->slug) }}" class="block group">
                            <div class="aspect-[4/3] overflow-hidden bg-shell">
                                <img src="{{ $article->cover_image ?: '/placeholder/journal-' . $article->slug . '.svg' }}"
                                     alt="{{ $article->title }}" loading="lazy" decoding="async"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                            <div class="pt-4">
                                <div class="flex items-center gap-3 text-[11px] uppercase tracking-widest text-graphite">
                                    @if ($article->category)
                                        <span class="text-brass">{{ strtoupper($article->category) }}</span>
                                    @endif
                                    <span>{{ $article->published_at?->format('d.m.Y') }}</span>
                                </div>
                                <h2 class="display-campaign text-2xl mt-2 group-hover:underline underline-offset-4 decoration-brass">{{ $article->title }}</h2>
                                @if ($article->excerpt)
                                    <p class="mt-2 text-sm text-graphite leading-relaxed">{{ $article->excerpt }}</p>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
            {{ $articles->links() }}
        @endif
    </div>
@endsection