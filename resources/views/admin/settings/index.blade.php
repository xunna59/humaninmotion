@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">Settings</h1>
        <p class="text-graphite mt-1">Store-wide configuration</p>
    </header>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Announcement bar</h2>
            <div>
                <label class="label" for="announcement_text">Text</label>
                <input class="input" id="announcement_text" name="announcement_text" value="{{ old('announcement_text', $settings->get('announcement_text')) }}">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="announcement_enabled" value="1" class="checkbox" @checked((bool) $settings->get('announcement_enabled'))>
                Show announcement bar
            </label>
        </div>

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Commerce</h2>
            <div>
                <label class="label" for="free_shipping_threshold">Free UK shipping threshold (£)</label>
                <input class="input" type="number" step="0.01" min="0" id="free_shipping_threshold" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings->get('free_shipping_threshold')) }}">
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Branding</h2>
            <div>
                <label class="label" for="logo">Site logo</label>
                <input class="input" type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                <p class="mt-1 text-xs text-graphite">PNG, JPG, WebP or SVG up to 2MB. Leave empty to keep the current logo or wordmark.</p>
            </div>
            @if ($settings->logoUrl())
                <div class="flex items-center gap-4">
                    <img src="{{ $settings->logoUrl() }}" alt="Current site logo" class="h-10 w-auto object-contain border border-ink/10 bg-white p-1">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="remove_logo" value="1" class="checkbox">
                        Remove logo and use wordmark
                    </label>
                </div>
            @endif
        </div>

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">GDPR cookie consent</h2>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="consent_enabled" value="1" class="checkbox" @checked((bool) $settings->get('consent_enabled'))>
                Show cookie consent banner
            </label>
            <div>
                <label class="label" for="consent_text">Banner text</label>
                <textarea class="input" id="consent_text" name="consent_text" rows="3">{{ old('consent_text', $settings->get('consent_text')) }}</textarea>
            </div>
            <div>
                <label class="label" for="consent_policy_slug">Cookie policy page slug</label>
                <input class="input" id="consent_policy_slug" name="consent_policy_slug" value="{{ old('consent_policy_slug', $settings->get('consent_policy_slug')) }}">
                <p class="mt-1 text-xs text-graphite">Slug of the storefront page that details your cookie policy.</p>
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Company</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="company_name">Name</label>
                    <input class="input" id="company_name" name="company_name" value="{{ old('company_name', $settings->get('company_name')) }}">
                </div>
                <div>
                    <label class="label" for="email">Contact email</label>
                    <input class="input" type="email" id="email" name="email" value="{{ old('email', $settings->get('email')) }}">
                </div>
                <div>
                    <label class="label" for="phone">Phone</label>
                    <input class="input" id="phone" name="phone" value="{{ old('phone', $settings->get('phone')) }}">
                </div>
                <div>
                    <label class="label" for="vat_number">VAT number</label>
                    <input class="input" id="vat_number" name="vat_number" value="{{ old('vat_number', $settings->get('vat_number')) }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="label" for="address">Address</label>
                    <input class="input" id="address" name="address" value="{{ old('address', $settings->get('address')) }}">
                </div>
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">Social links</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                @php $social = $settings->social(); @endphp
                @foreach ($socials as $socialKey)
                    <div>
                        <label class="label" for="social_{{ $socialKey }}">{{ ucfirst($socialKey) }}</label>
                        <input class="input" type="url" id="social_{{ $socialKey }}" name="social[{{ $socialKey }}]" value="{{ old('social.' . $socialKey, $social[$socialKey] ?? '') }}" placeholder="https://">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm">SEO</h2>
            <div>
                <label class="label" for="seo_default_title">Default meta title</label>
                <input class="input" id="seo_default_title" name="seo_default_title" value="{{ old('seo_default_title', $settings->get('seo_default_title')) }}">
            </div>
            <div>
                <label class="label" for="seo_default_description">Default meta description</label>
                <textarea class="input" id="seo_default_description" name="seo_default_description" rows="3">{{ old('seo_default_description', $settings->get('seo_default_description')) }}</textarea>
            </div>
        </div>

        <button class="btn-primary">Save settings</button>
    </form>
@endsection