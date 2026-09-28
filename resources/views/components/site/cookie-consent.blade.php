@inject('site', 'App\Services\SettingsService')

@if ((bool) $site->get('consent_enabled', true))
    <div
        x-data="{
            show: false,
            init() {
                const hasChoice = document.cookie.split(';').some(c => c.trim().startsWith('hm_cookie_consent='));
                if (! hasChoice) {
                    this.show = true;
                }
                window.addEventListener('consent:open', () => { this.show = true; });
            },
            setConsent(value) {
                document.cookie = 'hm_cookie_consent=' + value + '; max-age=31536000; path=/; SameSite=Lax';
                this.show = false;
            },
        }"
        x-cloak
        x-show="show"
        x-transition.opacity.duration.200
        role="region"
        aria-label="Cookie consent"
        class="fixed inset-x-0 bottom-0 z-[70] border-t border-ink/10 bg-bone shadow-[0_-8px_30px_rgba(0,0,0,0.08)]"
    >
        <div class="container-site py-5 lg:py-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex-1 space-y-1.5">
                    <p class="text-sm font-semibold tracking-[--tracking-label] uppercase">Your privacy</p>
                    <p class="text-[13px] leading-relaxed text-graphite">{{ $site->get('consent_text') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('page.show', $site->get('consent_policy_slug', 'cookie-policy')) }}" class="text-[13px] underline underline-offset-4 decoration-brass hover:text-brass-dark">Cookie Policy</a>
                    <button type="button" class="btn btn-ghost btn-sm" @click="setConsent('necessary')">Necessary only</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="setConsent('all')">Accept all</button>
                </div>
            </div>
        </div>
    </div>
@endif