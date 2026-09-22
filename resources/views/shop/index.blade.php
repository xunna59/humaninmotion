@extends('layouts.site')

@section('title', $title)

@section('content')
    @include('components.shop.breadcrumbs', ['crumbs' => $crumbs ?? [], 'current' => $heading])

    <header class="bg-shell">
        <div class="container-site py-10 lg:py-14">
            <h1 class="display-campaign text-4xl lg:text-6xl">{{ $heading }}</h1>
            @if (! empty($description))
                <p class="mt-3 max-w-2xl text-ink/70">{{ $description }}</p>
            @endif
        </div>
    </header>

    <x-shop.toolbar :products="$products" :filters="$filters" :facets="$facets" />

    <div class="container-site py-8 lg:py-10">
        <div class="grid lg:grid-cols-[240px_1fr] gap-10">
            {{-- Desktop filters --}}
            <aside class="hidden lg:block">
                <div class="sticky top-24 space-y-7">
                    <div class="flex items-center justify-between">
                        <p class="label">FILTERS</p>
                        <a href="{{ url()->current() }}" class="text-[11px] uppercase tracking-wider text-brass hover:underline">Clear all</a>
                    </div>
                    @include('shop._filters', ['filters' => $filters, 'facets' => $facets])
                </div>
            </aside>

            {{-- Product grid --}}
            <div>
                <x-shop.product-grid :products="$products" />
            </div>
        </div>
    </div>

    {{-- Mobile filter drawer --}}
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