<button type="button"
        wire:click="toggle"
        title="{{ $active ? 'Remove from wishlist' : 'Add to wishlist' }}"
        aria-label="{{ $active ? 'Remove from wishlist' : 'Add to wishlist' }}"
        aria-pressed="{{ $active ? 'true' : 'false' }}"
        class="w-9 h-9 inline-flex items-center justify-center rounded-full bg-bone/90 text-ink shadow-sm border border-ink/10 transition-colors hover:text-sale">
    <x-icon name="{{ $active ? 'heart-fill' : 'heart' }}" size="17" class="{{ $active ? 'text-sale' : '' }}" />
</button>