<section class="relative bg-ink overflow-hidden">
    @php
        $img = $section->image_desktop ?: '/placeholder/promo.svg';
        $ratio = $section->image_width ?? 16;
        $ratioH = $section->image_height ?? 7;
    @endphp
    <div class="absolute inset-0">
        <img src="{{ $img }}" alt="" loading="lazy" decoding="async"
             class="h-full w-full object-cover opacity-60">
        <div class="absolute inset-0 bg-ink/40"></div>
    </div>
    <div class="container-site relative py-20 lg:py-28 flex justify-{{ $section->alignmentClass() === 'center' ? 'center text-center' : 'start text-left' }}">
        <div class="max-w-2xl {{ $section->alignmentClass() === 'center' ? 'mx-auto text-center' : '' }}">
            <p class="eyebrow text-brass mb-3">{{ $section->content['subtext'] ?? 'LIMITED EDITION' }}</p>
            <h2 class="display-campaign text-bone text-4xl lg:text-6xl">{{ $section->title }}</h2>
            <p class="mt-4 text-bone/80 max-w-lg {{ $section->alignmentClass() === 'center' ? 'mx-auto' : '' }}">{{ $section->description }}</p>
            @if ($section->button_text && $section->button_url)
                <a href="{{ $section->button_url }}" class="mt-8 inline-block btn btn-primary bg-brass border-brass text-ink hover:bg-bone hover:border-bone">{{ $section->button_text }}</a>
            @endif
        </div>
    </div>
</section>