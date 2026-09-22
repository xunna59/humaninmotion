@inject('navigation', 'App\Services\NavigationService')

<header class="relative z-40 bg-bone border-b border-ink/10" x-data="megaMenu()" @mouseleave="active = null">
    <div class="container-site flex items-center h-16 lg:h-[70px] gap-3 lg:gap-8">

        <button class="lg:hidden p-1 -ml-1" @click="$store.app.mobileOpen = true" aria-label="Open menu">
            <x-icon name="menu" size="22" />
        </button>

        <a href="{{ route('home') }}" class="shrink-0 font-display text-[20px] lg:text-[22px] tracking-[0.16em] pt-1" aria-label="Human In Motion">
            HUMAN <span class="text-brass">IN</span> MOTION
        </a>

        <nav class="hidden lg:flex items-center gap-6 xl:gap-8 mx-auto" aria-label="Primary">
            @foreach ([
                ['key' => 'shop', 'label' => 'SHOP', 'url' => route('shop')],
                ['key' => 'new', 'label' => 'NEW IN', 'url' => route('new-in')],
                ['key' => 'collections', 'label' => 'COLLECTIONS', 'url' => route('collections.index')],
                ['key' => 'clothing', 'label' => 'CLOTHING', 'url' => route('shop')],
                ['key' => 'accessories', 'label' => 'ACCESSORIES', 'url' => route('shop')],
                ['key' => 'sale', 'label' => 'SALE', 'url' => route('sale'), 'accent' => true],
            ] as $item)
                <a href="{{ $item['url'] }}"
                   @mouseenter="active = '{{ $item['key'] }}'"
                   :class="active === '{{ $item['key'] }}' ? 'text-ink after:scale-x-100' : 'text-ink/80 hover:text-ink'"
                   class="relative py-3 text-[12px] font-semibold tracking-[--tracking-label] uppercase transition-colors after:absolute after:left-0 after:-bottom-px after:h-[2px] after:w-full after:bg-brass after:origin-left after:scale-x-0 after:transition-transform {{ $item['accent'] ?? false ? 'text-brass-dark' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3 lg:gap-4 ml-auto lg:ml-0 shrink-0">
            <a href="{{ route('search') }}" class="p-1.5 text-ink/80 hover:text-ink transition-colors" aria-label="Search" data-search-trigger>
                <x-icon name="search" size="20" />
            </a>
            <a href="{{ route('account.wishlist') }}" class="hidden sm:inline-flex p-1.5 text-ink/80 hover:text-ink transition-colors" aria-label="Wishlist">
                <x-icon name="heart" size="20" />
            </a>
            <a href="{{ route('account.index', [], false) }}" class="hidden sm:inline-flex p-1.5 text-ink/80 hover:text-ink transition-colors" aria-label="Account">
                <x-icon name="user" size="20" />
            </a>
            <livewire:cart-badge />
        </div>
    </div>

    <div @mouseenter="active = active" class="absolute inset-x-0 top-full bg-bone border-b border-ink/10 hidden lg:block"
         x-show="active" x-transition.opacity.duration.150ms x-cloak>
        <div class="container-site py-8 grid gap-10"
             x-show="active === 'shop'" x-cloak>
            <div>
                <p class="eyebrow text-graphite mb-4">SHOP</p>
                <ul class="space-y-3">
                    @foreach ($navigation->shopLinks() as $link)
                        <li>
                            <a href="{{ $link['url'] }}" class="text-[13px] text-ink/80 hover:text-ink hover:underline underline-offset-4 decoration-brass">
                                {{ $link['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="eyebrow text-graphite mb-4">COLLECTIONS</p>
                <ul class="space-y-3">
                    @foreach ($navigation->collections() as $collection)
                        <li>
                            <a href="{{ route('collection.show', $collection->slug) }}" class="text-[13px] text-ink/80 hover:text-ink">
                                {{ $collection->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-span-2">
                <a href="{{ route('collection.show', 'signature') }}" class="group relative block h-[300px] overflow-hidden bg-ink">
                    <img src="/placeholder/mega-signature.svg" alt="Signature Collection" loading="lazy"
                         class="absolute inset-0 h-full w-full object-cover opacity-60 scale-105 group-hover:scale-100 transition-transform duration-500">
                    <div class="absolute inset-0 flex flex-col justify-end p-8">
                        <p class="eyebrow text-brass mb-2">SHOP NOW</p>
                        <p class="display-campaign text-3xl text-bone">THE SIGNATURE COLLECTION</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="container-site py-8 grid gap-10"
             x-show="active === 'collections'" x-cloak>
            <div>
                <p class="eyebrow text-graphite mb-4">COLLECTIONS</p>
                <ul class="space-y-3">
                    @foreach ($navigation->collections() as $collection)
                        <li>
                            <a href="{{ route('collection.show', $collection->slug) }}" class="text-[14px] text-ink/80 hover:text-ink">
                                {{ $collection->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-span-2">
                <a href="{{ route('collection.show', 'limited-edition') }}" class="group relative block h-[300px] overflow-hidden bg-ink">
                    <img src="/placeholder/mega-limited.svg" alt="Limited Edition" loading="lazy"
                         class="absolute inset-0 h-full w-full object-cover opacity-60 scale-105 group-hover:scale-100 transition-transform duration-500">
                    <div class="absolute inset-0 flex flex-col justify-end p-8">
                        <p class="eyebrow text-brass mb-2">SMALL RUNS</p>
                        <p class="display-campaign text-3xl text-bone">LIMITED EDITION</p>
                    </div>
                </a>
            </div>
        </div>

        @foreach (['clothing' => 'CLOTHING', 'accessories' => 'ACCESSORIES'] as $key => $heading)
            <div class="container-site py-8 grid gap-10"
                 x-show="active === '{{ $key }}'" x-cloak>
                <div>
                    <p class="eyebrow text-graphite mb-4">{{ $heading }}</p>
                    <ul class="space-y-3">
                        @foreach ($navigation->navCategories()[$key] ?? [] as $child)
                            <li>
                                <a href="{{ $child['url'] }}" class="text-[14px] text-ink/80 hover:text-ink">
                                    {{ $child['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="eyebrow text-graphite mb-4">SHOP {{ $heading }}</p>
                    <ul class="space-y-3">
                        @foreach ($navigation->shopLinks() as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-[13px] text-ink/70 hover:text-ink">
                                    {{ $link['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</header>