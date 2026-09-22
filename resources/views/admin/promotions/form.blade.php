@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">{{ $promotion->exists ? 'Edit: ' . $promotion->name : 'New promotion' }}</h1>
        <p class="text-graphite mt-1"><a href="{{ route('admin.promotions.index') }}" class="underline underline-offset-4">Promotions</a></p>
    </header>

    <form method="POST" action="{{ $promotion->exists ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}" class="max-w-3xl">
        @csrf
        @if ($promotion->exists)
            @method('PUT')
        @endif

        <div class="card p-5 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="name">Name</label>
                    <input class="input" id="name" name="name" value="{{ old('name', $promotion->name) }}" placeholder="Summer Campaign" required>
                </div>
                <div>
                    <label class="label" for="badge">Badge</label>
                    <input class="input" id="badge" name="badge" value="{{ old('badge', $promotion->badge) }}">
                </div>
                <div>
                    <label class="label" for="type">Type</label>
                    <select class="select" id="type" name="type">
                        @foreach (['percentage' => 'Percentage', 'fixed' => 'Fixed amount'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $promotion->type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="value">Value</label>
                    <input class="input" type="number" step="0.01" min="0" id="value" name="value" value="{{ old('value', $promotion->value) }}">
                </div>
                <div>
                    <label class="label" for="applies_to">Applies to</label>
                    <select class="select" id="applies_to" name="applies_to">
                        @foreach (['all' => 'All products', 'products' => 'Specific products', 'categories' => 'Specific categories', 'collections' => 'Specific collections'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('applies_to', $promotion->applies_to) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="starts_at">Starts</label>
                    <input class="input" type="date" id="starts_at" name="starts_at" value="{{ old('starts_at', $promotion->starts_at?->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="label" for="ends_at">Ends</label>
                    <input class="input" type="date" id="ends_at" name="ends_at" value="{{ old('ends_at', $promotion->ends_at?->format('Y-m-d')) }}">
                </div>
            </div>

            <div id="applies-targets" class="hidden">
                <label class="label">Targets</label>
                @foreach (['products' => 'Products', 'categories' => 'Categories', 'collections' => 'Collections'] as $kind => $kindLabel)
                    @php
                        $list = match ($kind) {
                            'products' => $products,
                            'categories' => $categories,
                            default => $collections,
                        };
                    @endphp
                    <div class="mb-3">
                        <p class="text-xs uppercase tracking-[--tracking-label] text-graphite mb-1">{{ $kindLabel }}</p>
                        <div class="grid sm:grid-cols-2 gap-2 max-h-44 overflow-y-auto">
                            @forelse ($list as $item)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="applies_ids[]" value="{{ $item->id }}" class="checkbox"
                                           @checked(in_array($item->id, $promotion->applies_ids ?? []))>
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
                    <input type="checkbox" name="is_active" value="1" class="checkbox" @checked(old('is_active', $promotion->is_active ?? true))>
                    Active
                </label>
                <button class="btn-primary">{{ $promotion->exists ? 'Save changes' : 'Create promotion' }}</button>
            </div>
        </div>
    </form>

    <script>
        function syncPromotion() {
            const v = document.getElementById('applies_to').value;
            document.getElementById('applies-targets').classList.toggle('hidden', v === 'all');
        }
        document.getElementById('applies_to')?.addEventListener('change', syncPromotion);
        syncPromotion();
    </script>
@endsection