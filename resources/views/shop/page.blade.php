@extends('layouts.site')

@section('title', $title)
@section('meta_description', $page->meta_description)

@section('content')
    <div class="bg-ink text-bone">
        <div class="container-site py-14 lg:py-20">
            <h1 class="display-campaign text-4xl lg:text-6xl">{{ $page->title }}</h1>
        </div>
    </div>

    <div class="container-site max-w-3xl py-10 lg:py-14">
        <div class="prose-editorial">
            {!! $page->content !!}
        </div>
    </div>
@endsection