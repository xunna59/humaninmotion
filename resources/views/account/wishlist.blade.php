@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12 max-w-4xl">
        <header class="mb-8">
            <p class="eyebrow text-brass mb-2">MY ACCOUNT</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">WISHLIST</h1>
        </header>

        @include('account._nav')

        <div class="mt-8">
            @if ($items->isEmpty())
                <p class="text-sm text-graphite py-10 text-center border border-dashed border-ink/15">Your wishlist is empty.</p>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-3 gap-y-8">
                    @foreach ($items as $item)
                        @php $product = $item->product ?? $item; @endphp
                        <div class="relative">
                            <form action="{{ route('account.wishlist.remove', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                                @csrf
                                <button type="submit" class="w-9 h-9 inline-flex items-center justify-center rounded-full bg-bone/90 text-sale border border-ink/10 hover:bg-sale hover:text-bone transition-colors" aria-label="Remove {{ $product->name }}">
                                    <x-icon name="close" size="16" />
                                </button>
                            </form>
                            <x-shop.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection