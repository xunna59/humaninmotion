<div x-data="{ panel: false }" class="relative" @click.outside="panel = false">
    @if (count($colours) > 0 && $sizes->isNotEmpty())
        <button
            @click="panel = !panel"
            @keydown.escape="panel = false"
            class="btn btn-primary btn-block btn-sm justify-center"
            aria-haspopup="true" :aria-expanded="panel">
            @if ($adding)
                <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-bone/40 border-t-bone"></span>
            @else
                QUICK ADD
            @endif
        </button>

        <div x-show="panel" x-cloak x-transition.opacity.duration.120ms
             class="absolute inset-x-0 bottom-full mb-2 z-30 bg-white border border-ink/15 p-4 shadow-xl">
            <div>
                <p class="label m-0 text-[10px] mb-2">COLOUR</p>
                <div class="flex gap-2 mb-4">
                    @foreach ($colours as $index => $c)
                        <button type="button" wire:click="selectColour('{{ $c }}')"
                                class="w-6 h-6 rounded-full border transition-transform"
                                :class="{{ $colour == $c ? '1' : '0' }} ? 'ring-2 ring-brass ring-offset-2' : 'border-ink/25'"
                                wire:key="c-{{ $index }}" aria-label="Select {{ $c }}" aria-pressed="{{ $colour == $c ? 'true' : 'false' }}">
                            <span class="block w-full h-full rounded-full" style="background-color: {{ $colourHexes[strtolower(str_replace(' ', '_', $c))] ?? $colourHexes[str()->title($c)] ?? '#333' }}"></span>
                        </button>
                    @endforeach
                </div>

                <p class="label m-0 text-[10px] mb-2">SIZE</p>
                <div class="grid grid-cols-5 gap-2 max-h-32 overflow-y-auto">
                    @foreach ($sizes as $variant)
                        <button type="button" wire:click="selectSize('{{ $variant->size }}')"
                                :class="{{ $variant->size == $size ? '1' : '0' }} ? 'border-ink bg-ink text-white' : 'border-ink/25 hover:border-ink'"
                                wire:key="s-{{ $variant->id }}"
                                @if ($variant->stock <= 0) disabled @endif
                                class="h-9 border text-xs uppercase transition-colors {{ ($variant->stock <= 0) ? 'opacity-30 cursor-not-allowed border-ink/15' : '' }}">
                            {{ $variant->size }}
                        </button>
                    @endforeach
                </div>
            </div>

            @error('size')
                <p class="error-text">{{ $message }}</p>
            @enderror

            <button wire:click="addToBag" wire:loading.attr="disabled"
                    class="btn btn-primary btn-sm btn-block mt-3 justify-center">
                ADD TO BAG
            </button>
        </div>
    @elseif ($sizes->isNotEmpty())
        <button wire:click="addToBag" class="btn btn-primary btn-block btn-sm justify-center">
            @if ($adding)
                <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-bone/40 border-t-bone"></span>
            @else
                ADD TO BAG
            @endif
        </button>
    @endif
</div>