@inject('site', 'App\Services\SettingsService')
@inject('navigation', 'App\Services\NavigationService')
@inject('cartService', 'App\Services\Cart\CartService')

@if($announcement = $site->announcement())
    <div class="bg-ink text-bone text-center label-tracked py-2.5 px-4">{{ $announcement }}</div>
@endif

<x-site.header />
<x-site.mobile-nav />

<main id="main-content" class="flex-1">
    {{ $slot }}
</main>

<x-site.footer />

<livewire:cart-drawer />

@stack('scripts')