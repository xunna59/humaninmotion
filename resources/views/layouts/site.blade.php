@inject('site', 'App\Services\SettingsService')
@inject('navigation', 'App\Services\NavigationService')
@inject('cartService', 'App\Services\Cart\CartService')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(! empty($title)){{ $title }} · @endif{{ $site->get('seo_default_title', 'Human In Motion') }}</title>
    <meta name="description" content="{{ $metaDescription ?? $site->get('seo_default_description', '') }}">

    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? $site->get('seo_default_title')) }}">
    <meta property="og:description" content="{{ $metaDescription ?? $site->get('seo_default_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:site_name" content="Human In Motion">
    @isset($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endisset
    <meta name="robots" content="{{ isset($noindex) && $noindex ? 'noindex,follow' : 'index,follow' }}">

    @stack('head')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Bebas+Neue&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@livewireScriptConfig
</head>
<body class="min-h-screen flex flex-col bg-bone text-ink">

<x-site.shell>
    @yield('content')
</x-site.shell>

@stack('modals')
@stack('scripts')
</body>
</html>