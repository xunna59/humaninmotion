@php
    $hidden = collect($filters)
        ->filter(fn ($v, $k) => ! in_array($k, ['sizes', 'colours', 'in_stock', 'price_min', 'price_max']))
        ->filter()
        ->all();

    $colourHexes = \App\Models\Colour::query()->pluck('hex', 'slug')
        ->mapWithKeys(fn ($hex, $slug) => [strtoupper($slug) => $hex])
        ->all();
@endphp

<form method="GET" action="{{ url()->current() }}" role="filter">
    @foreach ($hidden as $key => $value)
        @if (is_array($value))
            @foreach ($value as $v)
                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    <div class="space-y-7">
        @if (collect($facets['colours'] ?? [])->isNotEmpty())
            <fieldset>
                <legend class="label mb-3">COLOUR</legend>
                <div class="grid grid-cols-6 gap-2">
                    @foreach ($facets['colours'] as $colour)
                        @php
                            $checked = in_array($colour, $filters['colours'] ?? [], true);
                            $hex = $colourHexes[$colour] ?? '#333';
                        @endphp
                        <label class="group relative cursor-pointer flex flex-col items-center gap-1" title="{{ $colour }}">
                            <input type="checkbox" name="colours[]" value="{{ $colour }}" @checked($checked)
                                   class="peer sr-only"
                                   onchange="this.form.submit()">
                            <span class="w-7 h-7 rounded-full border border-ink/15 transition-all peer-checked:ring-2 peer-checked:ring-brass peer-checked:ring-offset-2 peer-checked:ring-offset-bone"
                                  style="background-color: {{ $hex }};"></span>
                            <span class="opacity-0 group-hover:opacity-100 text-[8px] uppercase tracking-wide text-graphite transition-opacity">{{ $colour }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endif

        @if (collect($facets['sizes'] ?? [])->isNotEmpty())
            <fieldset>
                <legend class="label mb-3">SIZE</legend>
                <div class="flex flex-wrap gap-2">
                    @foreach ($facets['sizes'] as $size)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="sizes[]" value="{{ $size }}"
                                   @checked(in_array($size, $filters['sizes'] ?? [], true))
                                   class="peer sr-only" onchange="this.form.submit()">
                            <span class="peer-checked:bg-ink peer-checked:text-bone peer-checked:border-ink inline-flex items-center justify-center min-w-[36px] h-9 px-2 border border-ink/25 text-[11px] uppercase tracking-wide transition-colors">{{ $size }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endif

        <fieldset>
            <legend class="label mb-3">PRICE</legend>
            <div class="flex items-center gap-2">
                <input type="number" name="price_min" min="0" step="1" placeholder="£ min"
                       value="{{ $filters['price_min'] ?? null }}"
                       class="field field-sm w-1/2">
                <span class="text-graphite">–</span>
                <input type="number" name="price_max" min="0" step="1" placeholder="£ max"
                       value="{{ $filters['price_max'] ?? null }}"
                       class="field field-sm w-1/2">
            </div>
        </fieldset>

        <fieldset>
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" name="in_stock" value="1"
                       @checked(($filters['in_stock'] ?? false))
                       class="peer sr-only" onchange="this.form.submit()">
                <span class="w-5 h-5 border border-ink/30 flex items-center justify-center text-transparent peer-checked:bg-ink peer-checked:border-ink peer-checked:text-bone text-[12px] leading-none">&check;</span>
                <span class="text-[13px] uppercase tracking-wide">In Stock</span>
            </label>
        </fieldset>
    </div>
</form>