@extends('layouts.site')

@section('title', $title)
@section('meta_description', $product->meta_description)
@section('canonical', $product->canonical_url ?? url()->current())

@section('head')
    @if ($product->isOnSale())
        <meta name="robots" content="index,follow">
    @endif
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => $product->primaryImage()?->path,
        'description' => $product->short_description,
        'brand' => ['@type' => 'Brand', 'name' => $product->brand ?: 'Human In Motion'],
        'sku' => $product->defaultVariant()?->sku,
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'GBP',
            'price' => (float) $product->price,
            'itemCondition' => 'https://schema.org/NewCondition',
            'availability' => $product->totalStock() > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        ],
    ]) !!}</script>
@endsection

@section('content')
    <x-shop.breadcrumbs :crumbs="[['label' => 'Shop', 'url' => route('shop')], ['label' => $product->category?->name ?? 'Shop', 'url' => $product->category ? route('category.show', $product->category->slug) : route('shop')]]" :current="$product->name" />

    <div x-data="pdp()" x-init="init({ variants: {!! json_encode($product->variants->map(fn ($v) => [
        'id' => $v->id,
        'colour' => $v->colour,
        'size' => $v->size,
        'stock' => (int) $v->stock,
        'sku' => $v->sku,
        'price' => (float) $v->price,
        'compare_price' => $v->compare_price,
    ])) !!}, images: {!! json_encode($product->images->pluck('path')) !!} })">
    <script>window.PDP_ADD_URL = {{ json_encode(route('bag.add')) }};</script>
        <div class="container-site grid lg:grid-cols-2 gap-10 lg:gap-16 py-8 lg:py-12">
            {{-- Gallery --}}
            <div class="flex flex-col-reverse md:flex-row gap-4">
                <div class="flex md:flex-col gap-2 overflow-x-auto md:overflow-visible no-scrollbar md:w-20 shrink-0">
                    @foreach ($product->images as $image)
                        <button type="button" data-img="{{ $image->path }}"
                                @click="setImage('{{ $image->path }}')"
                                class="w-16 md:w-full aspect-[3/4] shrink-0 border overflow-hidden transition-colors"
                                :class="activeImage === '{{ $image->path }}' ? 'border-ink' : 'border-ink/10 hover:border-ink/40'">
                            <img src="{{ $image->path }}" alt="{{ $image->alt_text ?: $product->name }}" loading="lazy" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                <div class="relative aspect-[3/4] overflow-hidden bg-shell flex-1">
                    <template x-for="img in images" :key="img">
                        <img :src="img" :alt="img" x-show="activeImage === img"
                             x-transition.opacity.duration.200ms
                             class="absolute inset-0 h-full w-full object-cover">
                    </template>
                </div>
            </div>

            {{-- Summary --}}
            <div class="max-w-lg">
                @if ($product->is_limited || $product->is_exclusive)
                    <p class="label text-brass mb-2">
                        {{ $product->is_exclusive ? 'EXCLUSIVE' : 'LIMITED EDITION' }} — SMALL RUN
                    </p>
                @endif
                @if ($product->category)
                    <a href="{{ route('category.show', $product->category->slug) }}" class="text-[11px] uppercase tracking-widest text-graphite hover:text-ink">
                        {{ $product->category->name }}
                    </a>
                @endif
                <h1 class="display-campaign text-4xl lg:text-5xl mt-1">{{ $product->name }}</h1>

                <div class="flex items-baseline gap-3 mt-4">
                    <span class="text-xl font-semibold">{{ \App\Support\Money::formatFloat($product->price) }}</span>
                    @if ($product->compare_price > $product->price)
                        <span class="text-graphite line-through">{{ \App\Support\Money::formatFloat($product->compare_price) }}</span>
                        <span class="badge-sale">{{ $product->discountPercent() }}% OFF</span>
                    @endif
                </div>

                <p class="mt-4 text-ink/70 leading-relaxed">{{ $product->short_description }}</p>

                {{-- Swatches --}}
                @php $colourHexes = \App\Models\Colour::query()->pluck('hex', 'slug')->mapWithKeys(fn ($hex, $slug) => [strtoupper($slug) => $hex])->all(); @endphp
                <div class="mt-6">
                    <p class="label mb-2.5">COLOUR <span class="text-graphite" x-text="selectedColour ? (selectedColour + '') : ''"></span></p>
                    <div class="flex gap-2">
                        @foreach ($product->availableColours() as $index => $c)
                            <button type="button" @click="selectColour('{{ $c }}')"
                                    class="w-9 h-9 rounded-full border transition-transform"
                                    :class="selectedColour === '{{ $c }}' ? 'ring-2 ring-brass ring-offset-2' : 'border-ink/20'"
                                    :aria-pressed="selectedColour === '{{ $c }}'"
                                    aria-label="Colour {{ $c }}">
                                <span class="block w-full h-full rounded-full" style="background-color: {{ $colourHexes[$c] ?? '#333' }};"></span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Size --}}
                <div class="mt-6" data-pdp-size>
                    <div class="flex items-center justify-between">
                        <p class="label mb-2.5">SIZE</p>
                        <button type="button" @click="sizeChartOpen = true" class="text-[11px] uppercase tracking-wider text-brass underline underline-offset-4">
                            Size guide
                        </button>
                    </div>
                    <div x-show="sizeError && !selectedSize" x-cloak class="text-sale text-xs mb-1">Please select a size.</div>
                    <div class="grid grid-cols-6 gap-2" x-show="selectedColour">
                        <template x-for="v in variantsFor(selectedColour)" :key="v.id">
                            <button type="button" @click="selectSize(v.size)"
                                    :disabled="v.stock <= 0"
                                    :class="sizeClass(v.size, v.stock)"
                                    x-text="v.size"
                                    class="h-11 border text-xs uppercase tracking-wide transition-colors disabled:opacity-30 disabled:cursor-not-allowed w-full">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Add to bag --}}
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <button type="button" @click="addToBag"
                            :disabled="adding"
                            class="btn btn-primary flex-1 justify-center">
                        <span x-show="!adding">ADD TO BAG</span>
                        <span x-show="adding" x-cloak class="flex items-center gap-2">
                            <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-bone/40 border-t-bone"></span>
                            ADDING…
                        </span>
                    </button>
                    <livewire:wishlist-button :product="$product" :key="'pdp-w-' . $product->id" />
                </div>

                <div class="mt-4 space-y-2 text-xs text-graphite">
                    <p class="flex items-center gap-2"><x-icon name="truck" size="15" /> Free UK delivery over £100 · Express available</p>
                    <p class="flex items-center gap-2"><x-icon name="refresh" size="15" /> Free 60-day returns</p>
                    <p class="flex items-center gap-2"><x-icon name="lock" size="15" /> Secure checkout</p>
                </div>

                {{-- Details / accordions --}}
                <div class="mt-8 border-t divide-y divide-ink/10">

                    @if ($product->description)
                        <div x-data="accordion(false)">
                            <button @click="toggle()" :aria-expanded="open" class="w-full flex items-center justify-between py-4 text-[12px] uppercase tracking-widest">
                                Description
                                <x-icon name="chevron-down" size="16" x-show="!open" />
                                <x-icon name="chevron-up" size="16" x-show="open" x-cloak />
                            </button>
                            <div x-show="open" x-collapse class="text-sm text-ink/70 leading-relaxed pb-4">{{ $product->description }}</div>
                        </div>
                    @endif

                    @if ($product->care_instructions)
                        <div x-data="accordion(false)">
                            <button @click="toggle()" :aria-expanded="open" class="w-full flex items-center justify-between py-4 text-[12px] uppercase tracking-widest">
                                Care
                                <x-icon name="chevron-down" size="16" x-show="!open" />
                                <x-icon name="chevron-up" size="16" x-show="open" x-cloak />
                            </button>
                            <div x-show="open" x-collapse class="text-sm text-ink/70 leading-relaxed pb-4">{{ $product->care_instructions }}</div>
                        </div>
                    @endif

                    <div x-data="accordion(false)">
                        <button @click="toggle()" :aria-expanded="open" class="w-full flex items-center justify-between py-4 text-[12px] uppercase tracking-widest">
                            Shipping & Returns
                            <x-icon name="chevron-down" size="16" x-show="!open" />
                            <x-icon name="chevron-up" size="16" x-show="open" x-cloak />
                        </button>
                        <div x-show="open" x-collapse class="text-sm text-ink/70 leading-relaxed pb-4 space-y-2">
                            <p>UK Standard — £3.95 (free over £100, 2–4 working days).</p>
                            <p>UK Express — £7.95 (1–2 working days, before 1pm).</p>
                            <p>Free returns within 60 days.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Size chart modal --}}
        <div x-show="sizeChartOpen" x-cloak @keydown.escape.window="sizeChartOpen = false" x-data>
            <div class="fixed inset-0 bg-ink/60 z-[80]" @click="sizeChartOpen = false"></div>
            <div class="fixed inset-x-0 bottom-0 lg:inset-0 z-[90] flex items-end lg:items-center justify-center p-0 lg:p-6">
                <div class="bg-bone w-full max-w-xl mx-auto max-h-[85vh] overflow-y-auto" @click.outside="sizeChartOpen = false">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-ink/10 sticky top-0 bg-bone">
                        <p class="label">SIZE GUIDE</p>
                        <button class="p-1.5 text-ink/70 hover:text-ink" @click="sizeChartOpen = false" aria-label="Close">
                            <x-icon name="close" size="20" />
                        </button>
                    </div>
                    <div class="px-5 py-5">
                        @php
                            $chart = $product->category ? $product->category->sizeChart() : null;
                        @endphp
                        @if ($chart && ! empty($chart->rows))
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-ink/20">
                                            @foreach (array_keys((array) collect($chart->rows)->first()) as $th)
                                                <th class="py-2 px-3 text-left text-[11px] uppercase tracking-wider text-graphite">{{ \Illuminate\Support\Str::headline($th) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($chart->rows as $row)
                                            <tr class="border-b border-ink/10">
                                                @foreach ($row as $cell)
                                                    <td class="py-2 px-3 {{ $loop->first ? 'font-semibold' : '' }}">{{ is_array($cell) ? '' : $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-graphite">Size guide coming soon.</p>
                        @endif
                        <p class="mt-5 text-[11px] text-graphite uppercase tracking-wider">Measurements in cm, taken flat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($product->tags)
        <div class="container-site pb-8 -mt-2 flex flex-wrap gap-2">
            @foreach ($product->tags as $tag)
                <a href="{{ route('search', ['q' => $tag]) }}" class="text-[11px] uppercase tracking-wider text-graphite hover:text-ink">{{ $tag }}</a>
            @endforeach
        </div>
    @endif

    @if ($reviews->count() > 0)
        <section class="bg-shell">
            <div class="container-site py-12 lg:py-16">
                <h2 class="display-campaign text-3xl mb-6">REVIEWS</h2>
                <div class="max-w-2xl space-y-6">
                    @foreach ($reviews as $review)
                        <div class="border-b border-ink/10 pb-5">
                            <div class="flex items-center gap-3">
                                <span class="text-brass" aria-label="{{ $review->rating }} out of 5 stars">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </span>
                                <span class="text-sm font-semibold">{{ $review->user?->name ?? 'Verified Customer' }}</span>
                                <span class="text-xs text-graphite">· {{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if ($review->title)<p class="mt-2 text-sm font-medium">{{ $review->title }}</p>@endif
                            @if ($review->body)<p class="mt-1 text-sm text-ink/70">{{ $review->body }}</p>@endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($related->count())
        <x-shop.carousel :products="$related" title="COMPLETE THE LOOK" view-all-url="{{ $product->category ? route('category.show', $product->category->slug) : route('shop') }}" view-all-label="VIEW {{ strtoupper($product->category?->name ?? 'SHOP') }}" />
    @endif
@endsection