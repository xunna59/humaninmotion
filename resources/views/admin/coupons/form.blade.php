@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $coupon->exists ? 'Edit: ' . $coupon->code : 'New coupon' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.coupons.index') }}" class="underline underline-offset-4">Coupons</a></p>
    </header>

    <form method="POST" action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" class="max-w-3xl">
        @csrf
        @if ($coupon->exists)
            @method('PUT')
        @endif

        <div class="card p-5 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="code">Code</label>
                    <input class="input" id="code" name="code" value="{{ old('code', $coupon->code) }}" placeholder="WELCOME10" required>
                </div>
                <div>
                    <label class="label" for="type">Discount type</label>
                    <select class="select" id="type" name="type">
                        @foreach (['percentage' => 'Percentage', 'fixed' => 'Fixed amount', 'free_shipping' => 'Free shipping'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $coupon->type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="value">Value</label>
                    <input class="input" type="number" step="0.01" min="0" id="value" name="value" value="{{ old('value', $coupon->value) }}">
                </div>
                <div>
                    <label class="label" for="min_spend">Minimum spend (£)</label>
                    <input class="input" type="number" step="0.01" min="0" id="min_spend" name="min_spend" value="{{ old('min_spend', $coupon->min_spend) }}">
                </div>
                <div>
                    <label class="label" for="max_discount">Max discount (£)</label>
                    <input class="input" type="number" step="0.01" min="0" id="max_discount" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}">
                </div>
                <div>
                    <label class="label" for="usage_limit">Usage limit</label>
                    <input class="input" type="number" min="1" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                </div>
                <div>
                    <label class="label" for="per_customer_limit">Per customer limit</label>
                    <input class="input" type="number" min="1" id="per_customer_limit" name="per_customer_limit" value="{{ old('per_customer_limit', $coupon->per_customer_limit) }}">
                </div>
                <div>
                    <label class="label" for="starts_at">Starts</label>
                    <input class="input" type="date" id="starts_at" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="label" for="ends_at">Ends</label>
                    <input class="input" type="date" id="ends_at" name="ends_at" value="{{ old('ends_at', $coupon->ends_at?->format('Y-m-d')) }}">
                </div>
            </div>

            <div>
                <label class="label" for="applies_to">Applies to</label>
                <select class="select" id="applies_to" name="applies_to">
                    @foreach (['all' => 'All products', 'products' => 'Specific products', 'categories' => 'Specific categories', 'collections' => 'Specific collections'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('applies_to', $coupon->applies_to ?? 'all') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div id="applies-targets" class="hidden">
                <label class="label">Targets — tick items the discount applies to</label>
                @foreach (['products' => 'Products', 'categories' => 'Categories', 'collections' => 'Collections'] as $kind => $kindLabel)
                    @php
                        $list = match ($kind) {
                            'products' => $products ?? collect(),
                            'categories' => $categories ?? collect(),
                            default => $collections ?? collect(),
                        };
                    @endphp
                    <div class="mb-3">
                        <p class="text-xs uppercase tracking-[--tracking-label] text-graphite mb-1">{{ $kindLabel }}</p>
                        <div class="grid sm:grid-cols-2 gap-2 max-h-44 overflow-y-auto">
                            @forelse ($list as $item)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="applies_ids[]" value="{{ $item->id }}" class="checkbox"
                                           @checked(in_array($item->id, old('applies_ids', $coupon->applies_ids ?? [])))>
                                    {{ $item->name }}
                                </label>
                            @empty
                                <p class="text-graphite text-sm">None available.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" class="checkbox" @checked(old('is_active', $coupon->is_active ?? true))>
                    Active
                </label>
                <button class="btn-primary">{{ $coupon->exists ? 'Save changes' : 'Create coupon' }}</button>
            </div>
        </div>
    </form>

    <script>
        function sync() {
            const v = document.getElementById('applies_to').value;
            document.getElementById('applies-targets').classList.toggle('hidden', v === 'all');
        }
        document.getElementById('applies_to')?.addEventListener('change', sync);
        sync();
    </script>
@endsection