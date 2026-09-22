@extends('layouts.site')

@section('title', $title ?? null)

@section('head')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Human In Motion',
        'url' => url('/'),
    ]) !!}</script>
@endsection

@section('content')
    @foreach ($sections as $block)
        @php $section = $block['section']; @endphp

        @switch($section->type)
            @case('hero')
                @include('partials.home.hero', ['section' => $section])
                @break

            @case('product_carousel')
            @case('product_grid')
                <x-shop.carousel
                    :products="$block['products']"
                    :title="$section->title"
                    :description="$section->description"
                    view-all-url="{{ $section->button_url }}"
                    view-all-label="{{ $section->button_text ?: 'VIEW ALL' }}" />
                @break

            @case('category_grid')
                @include('partials.home.category-grid', ['section' => $section, 'categories' => $block['categories']])
                @break

            @case('editorial')
                @include('partials.home.editorial', ['section' => $section])
                @break

            @case('collection_tiles')
                @include('partials.home.collection-tiles', ['section' => $section, 'collections' => $block['collections']])
                @break

            @case('promotional_banner')
                @include('partials.home.pbanner', ['section' => $section])
                @break

            @case('image_banner')
                @include('partials.home.ibanner', ['section' => $section])
                @break

            @case('newsletter')
                @include('partials.home.newsletter', ['section' => $section])
                @break

            @default
                @if (! empty($section->content['html']))
                    <section class="container-site py-10 prose-editorial">{!! $section->content['html'] !!}</section>
                @endif
        @endswitch
    @endforeach
@endsection