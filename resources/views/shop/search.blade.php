@extends('layouts.site')

@section('title', $title)

@section('content')
    <x-shop.breadcrumbs :current="$term ? ('Search: ' . $term) : null" />

    @if ($term === '')
        <div class="container-site py-16 lg:py-24 max-w-2xl">
            <h1 class="display-campaign text-4xl lg:text-5xl">SEARCH</h1>
            <p class="mt-3 text-ink/70">Find pieces by name, style, colour or collection.</p>
            <form action="{{ route('search') }}" method="GET" class="mt-8 flex gap-2">
                <input type="search" name="q" value=""
                       placeholder="Try 'Hoodies', 'Cargo', 'T-Shirt'…"
                       class="field flex-1" aria-label="Search">
                <button type="submit" class="btn btn-primary shrink-0">SEARCH</button>
            </form>
            <p class="label mt-10 mb-3">POPULAR SEARCHES</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($popular as $p)
                    <a href="{{ route('search', ['q' => $p]) }}" class="border border-ink/20 px-3 py-1.5 text-[11px] uppercase tracking-wider hover:border-ink hover:bg-ink hover:text-bone transition-colors">{{ $p }}</a>
                @endforeach
            </div>
        </div>
    @else
        <div class="container-site py-10 lg:py-14">
            <h1 class="display-campaign text-4xl">RESULTS FOR “{{ $term }}”</h1>
            <p class="mt-2 text-sm text-graphite">{{ $products->total() }} {{ $products->total() === 1 ? 'piece' : 'pieces' }} found.</p>
        </div>

        <section class="container-site pb-16">
            @if (($suggestions['categories'] ?? collect())->isNotEmpty() || ($suggestions['collections'] ?? collect())->isNotEmpty())
                <div class="flex flex-wrap gap-3 mb-8">
                    @foreach ($suggestions['categories'] as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="border border-ink/20 px-4 py-2 text-xs uppercase tracking-wider hover:border-ink hover:bg-ink hover:text-bone transition-colors">
                            {{ $cat->name }} <span class="opacity-50">({{ $cat->products_count }})</span>
                        </a>
                    @endforeach
                    @foreach ($suggestions['collections'] as $col)
                        <a href="{{ route('collection.show', $col->slug) }}" class="border border-ink/20 px-4 py-2 text-xs uppercase tracking-wider hover:border-ink hover:bg-ink hover:text-bone transition-colors">
                            {{ $col->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <x-shop.product-grid :products="$products" />

            @if (! empty($products) && $products->isEmpty())
                <div class="py-10 text-center">
                    <p class="display-campaign text-3xl mb-2">NO MATCHES.</p>
                    <p class="text-sm text-graphite">Try a different search term.</p>
                </div>
            @endif
        </section>
    @endif
@endsection