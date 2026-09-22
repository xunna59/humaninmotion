@inject('site', 'App\Services\SettingsService')
@inject('navigation', 'App\Services\NavigationService')

<footer class="mt-16 bg-ink text-bone">
    <div class="container-site py-14 lg:py-20">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-10">
            <div class="col-span-2 space-y-5">
                <p class="font-display text-[24px] tracking-[0.14em]">HUMAN IN MOTION</p>
                <p class="max-w-sm text-sm leading-relaxed text-bone/60">
                    Premium modern British menswear. Designed for those who move differently.
                </p>
                <div class="flex items-center gap-3">
                    @foreach ([['i' => 'instagram', 'u' => $site->social()['instagram'] ?? '#'], ['i' => 'tiktok', 'u' => $site->social()['tiktok'] ?? '#'], ['i' => 'x-social', 'u' => $site->social()['x'] ?? '#'], ['i' => 'youtube', 'u' => $site->social()['youtube'] ?? '#']] as $social)
                        <a href="{{ $social['u'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 inline-flex items-center justify-center border border-bone/25 text-bone/70 hover:text-ink hover:bg-bone hover:border-bone transition-colors"
                           aria-label="Follow us on {{ $social['i'] }}">
                            <x-icon name="{{ $social['i'] }}" size="16" />
                        </a>
                    @endforeach
                </div>
            </div>

            @foreach ($navigation->footer() as $heading => $links)
                <div>
                    <p class="eyebrow text-bone/40 mb-4 pb-2 border-b border-bone/10">{{ $heading }}</p>
                    <ul class="space-y-2.5">
                        @foreach ($links as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-[13px] text-bone/70 hover:text-bone transition-colors">{{ $link['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-14 pt-8 border-t border-bone/10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
            <div class="max-w-md w-full">
                <p class="eyebrow text-bone/40 mb-3">JOIN HUMAN IN MOTION</p>
                <form class="flex gap-0" data-newsletter-form>
                    <input type="email" name="email" required placeholder="Email address"
                           class="flex-1 bg-transparent border border-bone/30 px-4 py-3 text-sm text-bone placeholder:text-bone/40 focus:border-bone focus:outline-none">
                    <button type="submit" class="btn btn-bone px-5 py-3">SIGN UP</button>
                </form>
                <p data-newsletter-message class="mt-2 text-xs text-bone/50"></p>
            </div>
            <p class="text-[11px] text-bone/40 tracking-wide">© {{ date('Y') }} Human In Motion Ltd. All rights reserved.</p>
        </div>
    </div>
</footer>