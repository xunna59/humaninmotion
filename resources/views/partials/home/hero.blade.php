<section class="relative bg-ink overflow-hidden">
    @php
        $desktop = $section->image_desktop;
        $mobile = $section->image_mobile ?: $desktop;
        $overlay = $section->overlay_strength ?? 50;
    @endphp

    <div class="absolute inset-0">
        <picture>
            @if ($desktop)
                <source media="(min-width: 768px)" srcset="{{ $desktop }}">
            @endif
            <img src="{{ $mobile ?: '/placeholder/hero-mobile.svg' }}"
                 alt="{{ $section->title }}"
                 class="h-full w-full object-cover"
                 fetchpriority="high" decoding="async">
        </picture>
        <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(10,10,10,{{ number_format($overlay / 100, 2) }}) 0%, rgba(10,10,10,0.15) 50%, rgba(10,10,10,0) 100%);"></div>
    </div>

    <div class="container-site relative min-h-[72vh] lg:min-h-[84vh] flex flex-col justify-center py-16 {{ $section->alignmentClass() }}">
        @if (! empty($section->content['subtext']))
            <p class="eyebrow text-brass mb-4">{{ $section->content['subtext'] }}</p>
        @endif
        <h1 class="display-campaign text-bone text-5xl sm:text-6xl lg:text-[96px] max-w-3xl">
            {{ $section->title }}
        </h1>
        @if ($section->description)
            <p class="mt-4 text-bone/80 text-base lg:text-lg max-w-md">{{ $section->description }}</p>
        @endif
        <div class="mt-8 flex flex-wrap gap-3">
            @if ($section->button_text && $section->button_url)
                <a href="{{ $section->button_url }}" class="btn btn-primary bg-bone border-bone text-ink text-bone:text-ink hover:bg-bone">{{ $section->button_text }}</a>
            @endif
            @if ($section->button_two_text && $section->button_two_url)
                <a href="{{ $section->button_two_url }}" class="btn btn-outline-bone">{{ $section->button_two_text }}</a>
            @endif
        </div>
    </div>
</section>