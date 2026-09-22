@inject('navigation', 'App\Services\NavigationService')
@inject('cartService', 'App\Services\Cart\CartService')

<div x-data="mobileNav()">
    <div x-show="open" x-cloak x-transition.opacity.duration.150ms
         class="fixed inset-0 z-50 bg-ink/50 lg:hidden" @click="open = false"></div>

    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-[85vw] max-w-[380px] bg-bone overflow-y-auto no-scrollbar lg:hidden">
        <div class="flex items-center justify-between h-16 px-5 border-b border-ink/10">
            <span class="font-display text-[17px] tracking-[0.14em]">HUMAN IN MOTION</span>
            <button class="p-1.5 text-ink/70" @click="open = false" aria-label="Close menu">
                <x-icon name="close" size="22" />
            </button>
        </div>

        <nav class="px-5 py-4" aria-label="Mobile">
            @php
                $mobileGroups = [
                    'Shop' => $navigation->shopLinks(),
                    'Clothing' => $navigation->navCategories()['clothing'] ?? [],
                    'Accessories' => $navigation->navCategories()['accessories'] ?? [],
                ];
            @endphp

            @foreach ($mobileGroups as $label => $links)
                <div class="border-b border-ink/10">
                    <button class="flex w-full items-center justify-between py-3.5 text-[13px] font-semibold tracking-[--tracking-label] uppercase"
                            @click="toggle('{{ \Illuminate\Support\Str::slug($label) }}')">
                        {{ $label }}
                        <x-icon name="chevron-down" size="16" class="transition-transform" x-bind:style="expanded['{{ \Illuminate\Support\Str::slug($label) }}'] ? 'transform: rotate(180deg)' : ''" />
                    </button>
                    <div x-show="expanded['{{ \Illuminate\Support\Str::slug($label) }}']" x-cloak x-collapse class="pb-3">
                        <ul class="space-y-2.5 pl-3">
                            @foreach ($links as $link)
                                <li><a href="{{ $link['url'] }}" class="block py-1 text-sm text-ink/75" @click="open = false">{{ $link['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

            <div class="border-b border-ink/10">
                <button class="flex w-full items-center justify-between py-3.5 text-[13px] font-semibold tracking-[--tracking-label] uppercase"
                        @click="toggle('collections')">
                    Collections
                    <x-icon name="chevron-down" size="16" x-bind:style="expanded['collections'] ? 'transform: rotate(180deg)' : ''" />
                </button>
                <div x-show="expanded['collections']" x-cloak x-collapse class="pb-3">
                    <ul class="space-y-2.5 pl-3">
                        @foreach ($navigation->collections() as $collection)
                            <li><a href="{{ route('collection.show', $collection->slug) }}" class="block py-1 text-sm text-ink/75" @click="open = false">{{ $collection->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <a href="{{ route('sale') }}" class="block py-3.5 text-[13px] font-semibold tracking-[--tracking-label] uppercase text-brass-dark border-b border-ink/10" @click="open = false">Sale</a>
            <a href="{{ route('new-in') }}" class="block py-3.5 text-[13px] font-semibold tracking-[--tracking-label] uppercase border-b border-ink/10" @click="open = false">New In</a>

            <div class="mt-5 space-y-2">
                <a href="{{ route('account.index', [], false) }}" class="btn btn-outline btn-sm btn-block" @click="open = false">
                    <x-icon name="user" size="15" /> My Account
                </a>
                <a href="{{ route('account.wishlist') }}" class="btn btn-ghost btn-sm btn-block" @click="open = false">
                    <x-icon name="heart" size="15" /> Wishlist
                </a>
            </div>
        </nav>
    </div>
</div>