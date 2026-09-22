@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12 max-w-4xl">
        <header class="mb-8">
            <p class="eyebrow text-brass mb-2">MY ACCOUNT</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">ADDRESSES</h1>
        </header>

        @include('account._nav')

        @if (session('success'))
            <div class="mt-6 border border-ok/40 bg-ok/10 text-ok px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <div class="mt-8 space-y-8">
            <section>
                <div class="grid sm:grid-cols-2 gap-4">
                    @forelse ($addresses as $address)
                        <div class="border border-ink/15 p-5 relative">
                            @if ($address->is_default)
                                <span class="absolute top-4 right-4 text-[10px] uppercase tracking-wider text-brass">Default</span>
                            @endif
                            <p class="text-[11px] uppercase tracking-widest text-graphite mb-2">{{ $address->type }}</p>
                            <p class="text-sm font-semibold">{{ $address->name }}</p>
                            <p class="text-sm text-graphite leading-relaxed mt-1">
                                {{ $address->line_one }}@if ($address->line_two)<br>{{ $address->line_two }}@endif<br>
                                {{ $address->city }}@if ($address->county), {{ $address->county }}@endif {{ $address->postcode }}<br>
                                {{ $address->country }}
                            </p>
                            <div class="mt-3 flex gap-4 text-[11px] uppercase tracking-wider">
                                <button type="button" class="text-brass hover:underline" onclick="document.getElementById('edit-address-{{ $address->id }}').classList.toggle('hidden')">
                                    Edit
                                </button>
                                <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Remove this address?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-graphite hover:text-sale">Remove</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-graphite border border-dashed border-ink/15 p-6">No saved addresses yet.</p>
                    @endforelse
                </div>
            </section>

            @foreach ($addresses as $address)
                <div id="edit-address-{{ $address->id }}" class="hidden border border-ink/15 p-6">
                    <h2 class="label mb-4">EDIT ADDRESS</h2>
                    <form action="{{ route('account.addresses.update', $address) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $address->type }}">
                        @include('account._address-form', ['address' => $address, 'prefix' => ''])
                        <button type="submit" class="btn btn-primary btn-sm">SAVE ADDRESS</button>
                    </form>
                </div>
            @endforeach

            <section class="border border-ink/15 p-6">
                <h2 class="label mb-4">ADD NEW ADDRESS</h2>
                <form action="{{ route('account.addresses.store') }}" method="POST">
                    @csrf
                    <div class="mb-4 flex items-center gap-3">
                        <label class="flex items-center gap-2 text-sm cursor-pointer select-none">
                            <input type="radio" name="type" value="shipping" checked class="accent-ink">
                            Shipping
                        </label>
                        <label class="flex items-center gap-2 text-sm cursor-pointer select-none">
                            <input type="radio" name="type" value="billing" class="accent-ink">
                            Billing
                        </label>
                    </div>
                    @include('account._address-form', ['address' => null, 'prefix' => ''])
                    <div class="mt-3">
                        <label class="flex items-center gap-3 text-sm cursor-pointer select-none">
                            <input type="checkbox" name="is_default" value="1" class="accent-ink">
                            Set as default
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-4">ADD ADDRESS</button>
                </form>
            </section>
        </div>
    </div>
@endsection