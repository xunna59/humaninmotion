@extends('layouts.site')

@section('title', $title)

@section('content')
    <header class="bg-ink text-bone">
        <div class="container-site py-16 lg:py-24">
            <p class="eyebrow text-brass mb-3">BUILT TO PERFORM</p>
            <h1 class="display-campaign text-5xl lg:text-7xl">THE COLLECTIONS</h1>
        </div>
    </header>

    <div class="bg-shell">
        <div class="container-site py-10 lg:py-16 space-y-16 lg:space-y-24">
            @forelse ($collections as $collection)
                <section id="collection-{{ $collection->slug }}" class="scroll-mt-24">
                    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
                        <div>
                            <h2 class="display-campaign text-3xl lg:text-4xl">{{ $collection->name }}</h2>
                            @if ($collection->description)
                                <p class="mt-2 text-sm text-graphite max-w-2xl">{{ $collection->description }}</p>
                            @endif
                        </div>
                        <a href="{{ route('collection.show', $collection->slug) }}" class="btn btn-outline btn-sm">SHOP COLLECTION</a>
                    </div>
                    <div class="flex gap-3 lg:gap-4 overflow-x-auto no-scrollbar snap-x snap-mandatory">
                        @foreach ($collection->products as $product)
                            <div class="snap-start shrink-0 w-[46vw] xs:w-[240px] sm:w-[250px] md:w-[270px]">
                                <x-shop.product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <p class="text-sm text-graphite text-center py-16">Collections are being curated. Check back soon.</p>
            @endforelse
        </div>
    </div>
@endsection