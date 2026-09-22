<section class="py-10 lg:py-16">
    <div class="container-site mb-8">
        <h2 class="display-campaign text-3xl lg:text-4xl">{{ $section->title ?? 'SHOP THE COLLECTION' }}</h2>
    </div>
    <div class="container-site grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
        @php
            $tiles = [
                ['label' => 'CLOTHING', 'url' => route('category.show', 'clothing'), 'img' => '/placeholder/cat-clothing.svg'],
                ['label' => 'ACCESSORIES', 'url' => route('category.show', 'accessories'), 'img' => '/placeholder/cat-accessories.svg'],
                ['label' => 'COLLECTIONS', 'url' => route('collections.index'), 'img' => '/placeholder/cat-collections.svg'],
            ];
        @endphp
        @foreach ($tiles as $tile)
            <a href="{{ $tile['url'] }}" class="group relative aspect-[4/5] overflow-hidden bg-ink block">
                <img src="{{ $tile['img'] }}" alt="{{ $tile['label'] }}" loading="lazy" decoding="async"
                     class="absolute inset-0 h-full w-full object-cover opacity-75 scale-105 group-hover:scale-110 group-hover:opacity-60 transition-all duration-500">
                <div class="absolute inset-0 flex items-end p-6">
                    <span class="display-campaign text-bone text-3xl">{{ $tile['label'] }}</span>
                </div>
            </a>
        @endforeach
    </div>
</section>