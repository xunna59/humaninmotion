@extends('layouts.site')

@section('title', $title)
@section('meta_description', $article->meta_description)

@section('content')
    <x-shop.breadcrumbs :crumbs="[['label' => 'Journal', 'url' => route('journal.index')]]" :current="$article->title" />

    <article class="container-site max-w-3xl py-10 lg:py-14">
        <div class="flex items-center gap-3 text-[11px] uppercase tracking-widest text-graphite mb-4">
            @if ($article->category)
                <span class="text-brass">{{ strtoupper($article->category) }}</span>
            @endif
            <span>{{ $article->published_at?->format('d.m.Y') }}</span>
            @if ($article->author)
                <span>· By {{ $article->author }}</span>
            @endif
        </div>

        <h1 class="display-campaign text-4xl lg:text-6xl">{{ $article->title }}</h1>

        @if ($article->excerpt)
            <p class="mt-5 text-lg text-ink/70 leading-relaxed">{{ $article->excerpt }}</p>
        @endif

        <figure class="my-8 overflow-hidden bg-shell">
            <img src="{{ $article->cover_image ?: '/placeholder/journal-' . $article->slug . '.svg' }}"
                 alt="{{ $article->title }}" class="w-full aspect-[16/9] object-cover" loading="lazy">
        </figure>

        <div class="prose-editorial">
            {!! $article->content !!}
        </div>

        @if (is_array($article->gallery) && count($article->gallery) > 0)
            <div class="grid grid-cols-2 gap-4 my-8">
                @foreach ($article->gallery as $image)
                    <img src="{{ $image }}" alt="" loading="lazy" class="w-full aspect-[4/3] object-cover">
                @endforeach
            </div>
        @endif
    </article>

    @if ($related->count())
        <div class="bg-shell">
            <div class="container-site py-12">
                <h2 class="display-campaign text-3xl mb-6">MORE STORIES</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($related as $rel)
                        <a href="{{ route('journal.show', $rel->slug) }}" class="group block">
                            <div class="aspect-[4/3] overflow-hidden bg-bone">
                                <img src="{{ $rel->cover_image ?: '/placeholder/journal-' . $rel->slug . '.svg' }}" alt="{{ $rel->title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                            <p class="display-campaign text-xl mt-3 group-hover:underline underline-offset-4 decoration-brass">{{ $rel->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection