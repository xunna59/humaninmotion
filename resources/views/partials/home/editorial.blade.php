<section class="py-10 lg:py-20">
    <div class="container-site grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
        <div class="relative aspect-[4/5] overflow-hidden bg-ink">
            <img src="{{ $section->image_desktop ?: '/placeholder/editorial-movement.svg' }}"
                 alt="{{ $section->title }}" loading="lazy" decoding="async"
                 class="absolute inset-0 h-full w-full object-cover">
        </div>
        <div class="max-w-md">
            <p class="eyebrow text-brass mb-4">THE SIGNATURE COLLECTION</p>
            <h2 class="display-campaign text-4xl lg:text-5xl">{{ $section->title }}</h2>
            <p class="mt-5 text-ink/70 leading-relaxed">{{ $section->description }}</p>
            @if ($section->button_text && $section->button_url)
                <a href="{{ $section->button_url }}" class="btn btn-outline mt-8">{{ $section->button_text }}</a>
            @endif
        </div>
    </div>
</section>