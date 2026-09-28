@extends('layouts.site')

@section('title', $title ?? 'Contact | Human In Motion')
@section('meta_description', 'Get in touch with Human In Motion — order enquiries, sizing and wholesale. We usually reply within one business day.')

@section('content')
    @inject('site', 'App\Services\SettingsService')

    <header class="bg-ink text-bone">
        <div class="container-site py-14 lg:py-20">
            <p class="eyebrow text-brass mb-3">GET IN TOUCH</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">CONTACT</h1>
        </div>
    </header>

    <div class="container-site max-w-3xl py-10 lg:py-14">
        <p class="text-lg text-ink/70 leading-relaxed">
            Questions about an order, sizing or a wholesale enquiry? We usually reply within one business day.
        </p>

        <div class="mt-10 grid sm:grid-cols-2 gap-6">
            @if ($site->get('address'))
                <div class="border border-ink/10 p-6">
                    <p class="eyebrow text-graphite mb-2">VISIT US</p>
                    <p class="text-sm leading-relaxed whitespace-pre-line">{{ $site->get('address') }}</p>
                </div>
            @endif
            @if ($site->get('email'))
                <div class="border border-ink/10 p-6">
                    <p class="eyebrow text-graphite mb-2">EMAIL US</p>
                    <a href="mailto:{{ $site->get('email') }}" class="text-sm underline underline-offset-4 decoration-brass hover:text-brass-dark">{{ $site->get('email') }}</a>
                </div>
            @endif
            @if ($site->get('phone'))
                <div class="border border-ink/10 p-6">
                    <p class="eyebrow text-graphite mb-2">CALL US</p>
                    <a href="tel:{{ preg_replace('/[^+\d]/', '', (string) $site->get('phone')) }}" class="text-sm underline underline-offset-4 decoration-brass hover:text-brass-dark">{{ $site->get('phone') }}</a>
                </div>
            @endif
            <div class="border border-ink/10 p-6">
                <p class="eyebrow text-graphite mb-2">HOURS</p>
                <p class="text-sm leading-relaxed">Monday – Friday: 9am – 6pm GMT</p>
            </div>
        </div>

        <div class="mt-10 p-6 bg-shell">
            <p class="text-sm text-ink/70 leading-relaxed">
                For an order already placed, please include your order number so we can help you faster.
            </p>
        </div>
    </div>
@endsection