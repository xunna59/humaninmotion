<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Human In Motion')</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-bone font-ui text-ink antialiased">
    <div class="min-h-screen flex flex-col lg:grid lg:grid-cols-2">
        <div class="relative hidden lg:block bg-ink overflow-hidden">
            <img src="/placeholder/account-side.svg" alt="" class="absolute inset-0 h-full w-full object-cover opacity-70">
            <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/30 to-transparent"></div>
            <div class="relative flex flex-col justify-between h-full p-10">
                <a href="{{ route('home') }}" class="display-campaign text-4xl text-bone">HUMAN IN MOTION</a>
                <div class="text-bone">
                    <p class="display-campaign text-4xl max-w-md leading-tight">BUILT TO PERFORM.<br>MADE TO LAST.</p>
                    <p class="mt-3 text-bone/70 text-sm">Premium essentials for the way you move.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col justify-center p-6 sm:p-10 lg:p-16">
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="display-campaign text-2xl">HUMAN IN MOTION</a>
            </div>
            <div class="w-full max-w-sm mx-auto">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>