@props(['products'])

@if ($products->isEmpty())
    <div class="py-24 text-center">
        <p class="display-campaign text-3xl mb-2">NOTHING FOUND.</p>
        <p class="text-sm text-graphite mb-6">Try clearing a filter or browse the full collection.</p>
        <a href="{{ url()->current() }}" class="btn btn-outline btn-sm">CLEAR FILTERS</a>
    </div>
@else
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-x-3 gap-y-8 lg:gap-x-4">
        @foreach ($products as $product)
            <x-shop.product-card :product="$product" wire:key="p-{{ $product->id }}" />
        @endforeach
    </div>
    {{ $products->links() }}
@endif