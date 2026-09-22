@props([
    'products' => null,
    'filters' => [],
    'facets' => [],
    'heading' => null,
    'description' => null,
    'id' => null,
])

<nav class="bg-bone">
    <div class="container-site py-3 border-b border-ink/10 flex items-center justify-between gap-3">
        <button @click="$store.app.mobileFilters = !$store.app.mobileFilters"
                class="lg:hidden text-[11px] uppercase tracking-wider flex items-center gap-2 border border-ink/25 px-3 py-2">
            <x-icon name="filter" size="14" />
            Filters
            <span x-text="$store.app.mobileFilters ? '×' : '+'"></span>
        </button>

        @if ($products)
            <p class="text-[11px] uppercase tracking-wider text-graphite">
                {{ $products->total() }} {{ $products->total() === 1 ? 'PIECE' : 'PIECES' }}
            </p>
        @endif

        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2 ml-auto">
            @foreach ($filters as $key => $value)
                @if (is_array($value))
                    @foreach ($value as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                    @endforeach
                @elseif ($value !== null && $value !== '')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <label for="sort-{{ $id ?: 'default' }}" class="sr-only">Sort by</label>
            <select name="sort" id="sort-{{ $id ?: 'default' }}"
                    onchange="this.form.submit()"
                    class="field field-sm w-auto">
                @foreach (\App\Services\Catalogue\ProductQuery::SORTS as $key => $label)
                    <option value="{{ $key }}" @selected(request('sort', 'featured') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>
</nav>