@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $method->exists ? 'Edit: ' . $method->name : 'New shipping method' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.shipping.index') }}" class="underline underline-offset-4">Shipping</a></p>
    </header>

    <form method="POST" action="{{ $method->exists ? route('admin.shipping.update', $method) : route('admin.shipping.store') }}" class="max-w-2xl">
        @csrf
        @if ($method->exists)
            @method('PUT')
        @endif

        <div class="card p-5 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="name">Name</label>
                    <input class="input" id="name" name="name" value="{{ old('name', $method->name) }}" required>
                </div>
                <div>
                    <label class="label" for="code">Code</label>
                    <input class="input" id="code" name="code" value="{{ old('code', $method->code) }}" placeholder="e.g. uk_standard">
                </div>
                <div>
                    <label class="label" for="price">Price (£)</label>
                    <input class="input" type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $method->price ?? 0) }}" required>
                </div>
                <div>
                    <label class="label" for="free_above">Free shipping over (£, blank for never)</label>
                    <input class="input" type="number" step="0.01" min="0" id="free_above" name="free_above" value="{{ old('free_above', $method->free_above ?? '') }}" placeholder="e.g. 100">
                </div>
                <div>
                    <label class="label" for="estimate">Delivery estimate</label>
                    <input class="input" id="estimate" name="estimate" value="{{ old('estimate', $method->estimate) }}" placeholder="e.g. 2–4 working days">
                </div>
                <div>
                    <label class="label" for="sort_order">Sort order</label>
                    <input class="input" type="number" min="0" id="sort_order" name="sort_order" value="{{ old('sort_order', $method->sort_order ?? 0) }}">
                </div>
            </div>

            <div>
                <label class="label" for="zones">Countries</label>
                <select class="select" id="zones" name="zones[]" multiple size="10">
                    @php
                        $selected = old('zones', $method->zones ?? []);
                    @endphp
                    @foreach ($countries as $country)
                        <option value="{{ $country->code }}" @selected(in_array($country->code, $selected, true))>{{ $country->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-graphite mt-1">Hold Cmd/Ctrl (Mac) or Ctrl (Windows) to select multiple countries.</p>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="checkbox" @checked(old('is_active', $method->is_active ?? true))>
                    Active (shown at checkout)
                </label>
            </div>
        </div>

        <div class="mt-6 flex gap-2">
            <button class="btn-primary">{{ $method->exists ? 'Save changes' : 'Create method' }}</button>
            <a href="{{ route('admin.shipping.index') }}" class="btn-bone">Cancel</a>
        </div>
    </form>
@endsection