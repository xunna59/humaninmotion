<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} – Human In Motion</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-shell text-ink">
    <div class="flex min-h-screen">
        <aside class="w-60 shrink-0 bg-ink text-bone flex flex-col fixed inset-y-0 left-0 z-40">
            <div class="px-6 py-6 border-b border-white/10">
                <a href="{{ route('admin.index') }}" class="font-display text-2xl tracking-[0.06em] text-bone">HIM</a>
                <p class="label-tracked text-fog/70 mt-1">Admin Console</p>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5 text-sm">
                @php
                    $items = [
                        'dashboard' => ['admin.dashboard', 'Dashboard'],
                        'products' => ['admin.products.index', 'Products'],
                        'categories' => ['admin.categories.index', 'Categories'],
                        'collections' => ['admin.collections.index', 'Collections'],
                        'promotions' => ['admin.promotions.index', 'Promotions'],
                        'coupons' => ['admin.coupons.index', 'Coupons'],
                        'orders' => ['admin.orders.index', 'Orders'],
                        'customers' => ['admin.customers.index', 'Customers'],
                        'reviews' => ['admin.reviews.index', 'Reviews'],
                        'returns' => ['admin.returns.index', 'Returns'],
                        'home' => ['admin.home.index', 'Homepage'],
                        'settings' => ['admin.settings.index', 'Settings'],
                    ];
                    $currentPrefix = explode('.', request()->route()?->getName() ?? '')[1] ?? '';
                @endphp
                @foreach ($items as $prefix => [$name, $label])
                    <a href="{{ route($name) }}"
                       class="flex items-center justify-between px-3 py-2 rounded-sm transition-colors {{ $currentPrefix === $prefix ? 'bg-bone/10 text-bone' : 'text-fog/80 hover:text-bone hover:bg-white/5' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="px-6 py-4 border-t border-white/10 text-xs space-y-2">
                <div class="flex items-center gap-2 text-fog/70">
                    <span class="w-2 h-2 rounded-full bg-ok"></span>
                    {{ auth()->user()->name }}
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="text-fog/70 hover:text-bone underline underline-offset-4">View store</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-fog/70 hover:text-bone underline underline-offset-4">Log out</button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 ml-60 px-8 py-8 max-w-[1400px]">
            @yield('content')
        </main>
    </div>
</body>
</html>