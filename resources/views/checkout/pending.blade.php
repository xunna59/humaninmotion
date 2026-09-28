@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-16 lg:py-24 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-brass/10 text-brass mb-6">
            <x-icon name="clock" size="30" />
        </div>

        <p class="eyebrow text-brass mb-3">PAYMENT PROCESSING</p>
        <h1 class="display-campaign text-5xl lg:text-7xl">ONE MOMENT...</h1>
        <p class="mt-4 max-w-lg mx-auto text-ink/70">
            We're confirming your payment for order <span class="font-semibold text-ink">#{{ $order->order_number }}</span>.
            This can take a moment — we'll email your confirmation as soon as it's complete.
        </p>

        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary">CONTINUE SHOPPING</a>
            <a href="{{ route('checkout.index') }}" class="btn btn-outline">BACK TO CHECKOUT</a>
        </div>

        <p class="mt-12 text-xs text-graphite">Human In Motion · Built to perform, made to last.</p>
    </div>
@endsection