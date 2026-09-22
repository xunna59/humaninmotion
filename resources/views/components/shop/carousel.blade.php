@props(['products', 'title' => null, 'description' => null, 'viewAllUrl' => null, 'viewAllLabel' => 'VIEW ALL'])

<section class="py-10 lg:py-16" x-data="carousel()">
    <div class="container-site">
        @if ($title || $description)
            <div class="flex items-end justify-between gap-6 mb-6">
                <div>
                    <h2 class="display-campaign text-3xl lg:text-4xl">{{ $title }}</h2>
                    @if ($description)
                        <p class="mt-2 text-sm text-graphite max-w-xl">{{ $description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    @if ($viewAllUrl)
                        <a href="{{ $viewAllUrl }}" class="btn btn-outline btn-sm">{{ $viewAllLabel }}</a>
                    @endif
                    <button class="hidden lg:inline-flex w-9 h-9 border border-ink/25 items-center justify-center hover:bg-ink hover:text-bone transition-colors" @click="scrollBy(-1)" aria-label="Scroll left">
                        <x-icon name="chevron-left" size="18" />
                    </button>
                    <button class="hidden lg:inline-flex w-9 h-9 border border-ink/25 items-center justify-center hover:bg-ink hover:text-bone transition-colors" @click="scrollBy(1)" aria-label="Scroll right">
                        <x-icon name="chevron-right" size="18" />
                    </button>
                </div>
            </div>
        @endif
    </div>

    @if ($products->isEmpty())
        <div class="container-site">
            <div class="border border-dashed border-ink/15 py-16 text-center text-sm text-graphite">
                No pieces available yet.
            </div>
        </div>
    @else
        <div class="overflow-x-auto no-scrollbar snap-x snap-mandatory" x-ref="track">
            <div class="flex gap-3 lg:gap-4 px-4 lg:px-10">
                @foreach ($products as $product)
                    <div class="snap-start shrink-0 w-[46vw] xs:w-[240px] sm:w-[250px] md:w-[270px]" wire:key="hc-{{ $product->id }}">
                        <x-shop.product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>