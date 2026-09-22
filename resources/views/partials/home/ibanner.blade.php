<section class="relative overflow-hidden">
    @php $img = $section->image_desktop ?: '/placeholder/sale-banner.svg'; @endphp
    <div class="absolute inset-0">
        <img src="{{ $img }}" alt="{{ $section->title }}" loading="lazy" decoding="async"
             class="h-full w-full object-cover">
    </div>
    <div class="container-site relative py-24 lg:py-32 flex justify-start">
        <div class="max-w-md">
            <p class="eyebrow text-brass mb-3">SEASONAL SALE</p>
            <h2 class="display-campaign text-5xl lg:text-6xl">{{ $section->title }}</h2>
            <p class="mt-4 text-ink/80">{{ $section->description }}</p>
            @if ($section->button_text && $section->button_url)
                <a href="{{ $section->button_url }}" class="mt-8 inline-block btn btn-primary bg-sale border-sale text-bone hover:bg-ink hover:border-ink">{{ $section->button_text }}</a>
            @endif
        </div>
    </div>
</section>