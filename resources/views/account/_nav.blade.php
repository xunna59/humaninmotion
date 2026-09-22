<nav class="flex gap-4 overflow-x-auto no-scrollbar border-b border-ink/10 text-[11px] uppercase tracking-widest">
    @php
        $tabs = [
            ['label' => 'Overview', 'route' => 'account.index'],
            ['label' => 'Orders', 'route' => 'account.orders'],
            ['label' => 'Addresses', 'route' => 'account.addresses'],
            ['label' => 'Wishlist', 'route' => 'account.wishlist'],
            ['label' => 'Details', 'route' => 'account.details'],
            ['label' => 'Password', 'route' => 'account.password'],
        ];
    @endphp
    @foreach ($tabs as $tab)
        <a href="{{ route($tab['route']) }}"
           class="whitespace-nowrap pb-3 border-b-2 transition-colors {{ request()->routeIs($tab['route']) || request()->routeIs($tab['route'] . '.show') || request()->routeIs($tab['route'] . '.store') || request()->routeIs($tab['route'] . '.update') || request()->routeIs($tab['route'] . '.destroy') ? 'border-brass text-ink' : 'border-transparent text-graphite hover:text-ink' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>