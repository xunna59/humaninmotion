@extends('layouts.site')

@section('title', $title)

@section('content')
    <x-shop.breadcrumbs :crumbs="[['label' => 'Collections', 'url' => route('collections.index')]]" />

    <header class="relative bg-ink overflow-hidden">
        @if ($collection->hero_image)
            <img src="{{ $collection->hero_image }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/30 to-ink/20"></div>
        @endif
        <div class="container-site relative py-16 lg:py-24">
            <p class="eyebrow text-brass mb-3">THE COLLECTION</p>
            <h1 class="display-campaign text-5xl lg:text-7xl text-bone">{{ $collection->name }}</h1>
            @if ($collection->description)
                <p class="mt-4 max-w-2xl text-bone/80">{{ $collection->description }}</p>
            @endif
            @if ($collection->promotional_text)
                <p class="mt-6 inline-block text-[11px] uppercase tracking-widest text-brass border border-brass/60 px-4 py-2">{{ $collection->promotional_text }}</p>
            @endif
        </div>
    </header>

    <x-shop.toolbar :products="$products" :filters="$filters" :facets="$facets" :id="$collection->slug" />

    <div class="container-site py-8 lg:py-10">
        <div class="grid lg:grid-cols-[240px_1fr] gap-10">
            <aside class="hidden lg:block">
                <div class="sticky top-24 space-y-7">
                    <div class="flex items-center justify-between">
                        <p class="label">FILTERS</p>
                        <a href="{{ url()->current() }}" class="text-[11px] uppercase tracking-wider text-brass hover:underline">Clear all</a>
                    </div>
                    @include('shop._filters', ['filters' => $filters, 'facets' => $facets])
                </div>
            </aside>
            <div>
                <x-shop.product-grid :products="$products" />
            </div>
        </div>
    </div>

    @include('partials.home.collection-tiles', [
        'section' => (object) ['title' => 'EXPLORE MORE', 'description' => null],
        'collections' => \App\Models\Collection::query()->where('is_active', true)
            ->where('id', '!=', $collection->id)->orderBy('position')->take(3)->get(),
    ])

    <div x-show="$store.app.mobileFilters" x-cloak @keydown.escape.window="$store.app.mobileFilters = false">
        <div class="fixed inset-0 bg-ink/50 z-[60]" @click="$store.app.mobileFilters = false"></div>
        <aside x-show="$store.app.mobileFilters" x-cloak
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
               class="fixed inset-y-0 right-0 z-[70] w-[88%] max-w-sm bg-bone flex flex-col">
            <div class="flex items-center justify-between px-5 h-16 border-b border-ink/10 shrink-0">
                <p class="label">FILTERS</p>
                <button class="p-1.5 text-ink/70 hover:text-ink" @click="$store.app.mobileFilters = false" aria-label="Close filters">
                    <x-icon name="close" size="22" />
                </button>
            </div>
            <div class="flex-1 overflow-y-auto px-5 py-5">
                @include('shop._filters', ['filters' => $filters, 'facets' => $facets])
            </div>
        </aside>
    </div>
@endsection