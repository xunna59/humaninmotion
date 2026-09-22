<section class="py-10 lg:py-16">
    <div class="container-site mb-8">
        <h2 class="display-campaign text-3xl lg:text-4xl">{{ $section->title ?? 'SHOP BY COLLECTION' }}</h2>
    </div>
    <div class="container-site grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
        @forelse ($collections as $collection)
            <a href="{{ route('collection.show', $collection->slug) }}" class="group relative aspect-[3/4] overflow-hidden bg-ink block">
                <img src="{{ $collection->hero_image ?: '/placeholder/collection-' . $collection->slug . '.svg' }}"
                     alt="{{ $collection->name }}" loading="lazy" decoding="async"
                     class="absolute inset-0 h-full w-full object-cover opacity-80 scale-105 group-hover:scale-110 group-hover:opacity-65 transition-all duration-500">
                <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-ink/70 via-transparent to-transparent">
                    <span class="display-campaign text-bone text-3xl">{{ $collection->name }}</span>
                    @if ($collection->promotional_text)
                        <p class="text-bone/70 text-sm mt-1">{{ $collection->promotional_text }}</p>
                    @endif
                </div>
            </a>
        @empty
            <p class="text-sm text-graphite">Collections coming soon.</p>
        @endforelse
    </div>
</section>