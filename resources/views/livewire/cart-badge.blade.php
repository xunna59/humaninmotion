<a href="{{ route('bag') }}" class="relative p-1.5 text-ink/80 hover:text-ink transition-colors inline-flex items-center" aria-label="Shopping bag">
    <x-icon name="bag" size="20" />
    @if ($count > 0)
        <span wire:key="badge-{{ $count }}" class="absolute -top-0.5 -right-0.5 min-w-[16px] h-[16px] px-[3px] inline-flex items-center justify-center rounded-full bg-ink text-bone text-[9px] font-bold">
            {{ $count }}
        </span>
    @endif
</a>