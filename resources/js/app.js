import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Livewire = Livewire;

Alpine.store('app', {
    mobileOpen: false,
    searchOpen: false,
    cartOpen: false,
    mobileFilters: false,
});

Alpine.data('drawer', (open = false) => ({
    open,
    init() {
        this.$watch('open', (v) => {
            document.body.style.overflow = v ? 'hidden' : '';
        });
    },
    openDrawer() {
        this.open = true;
    },
    closeDrawer() {
        this.open = false;
    },
}));

Alpine.data('modal', (open = false) => ({
    open,
    init() {
        this.$watch('open', (v) => {
            document.body.style.overflow = v ? 'hidden' : '';
        });
    },
    openModal() {
        this.open = true;
    },
    closeModal() {
        this.open = false;
    },
}));

Alpine.data('accordion', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
}));

Alpine.data('qty', (start = 1, max = 99) => ({
    qty: start,
    increment() {
        this.qty = Math.min(this.qty + 1, max);
    },
    decrement() {
        this.qty = Math.max(this.qty - 1, 1);
    },
}));

Alpine.data('megaMenu', () => ({
    active: null,
}));

Alpine.data('mobileNav', () => ({
    get open() {
        return Alpine.store('app').mobileOpen;
    },
    set open(v) {
        Alpine.store('app').mobileOpen = v;
    },
    expanded: {},
    init() {
        this.$watch('open', (v) => {
            document.body.style.overflow = v ? 'hidden' : '';
        });
    },
    toggle(key) {
        this.expanded[key] = !this.expanded[key];
    },
}));

Alpine.data('pdp', () => ({
    variants: [],
    images: [],
    activeImage: null,
    selectedColour: null,
    selectedSize: null,
    sizeError: false,
    adding: false,
    sizeChartOpen: false,

    init({ variants, images }) {
        this.variants = variants;
        this.images = images;
        this.activeImage = images[0] || null;
        this.selectedColour = variants.length ? variants[0].colour : null;
    },

    setImage(path) {
        this.activeImage = path;
    },

    variantsFor(colour) {
        const order = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        return this.variants
            .filter((v) => v.colour === colour)
            .sort((a, b) => {
                const ia = order.indexOf(a.size);
                const ib = order.indexOf(b.size);
                if (ia !== -1 && ib !== -1) return ia - ib;
                if (ia !== -1) return -1;
                if (ib !== -1) return 1;
                return String(a.size).localeCompare(String(b.size), undefined, { numeric: true });
            });
    },

    sizeClass(size, stock) {
        if (stock <= 0) return '';
        if (this.selectedSize === size) return '!border-ink !bg-ink !text-bone';
        return 'border-ink/25 hover:border-ink';
    },

    selectColour(colour) {
        this.selectedColour = colour;
        this.selectedSize = null;
        this.sizeError = false;
    },

    selectSize(size) {
        this.selectedSize = size;
        this.sizeError = false;
    },

    async addToBag() {
        if (!this.selectedSize) {
            this.sizeError = true;
            document.querySelector('[data-pdp-size]')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const variant = this.variants.find(
            (v) => v.colour === this.selectedColour && v.size === this.selectedSize
        );
        if (!variant) return;

        this.adding = true;
        try {
            const res = await fetch(window.PDP_ADD_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
                body: JSON.stringify({ variant_id: variant.id, quantity: 1 }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Could not add to bag');
            if (window.Livewire) {
                window.Livewire.dispatch('cart-updated');
                window.Livewire.dispatch('cart-open');
            }
        } finally {
            this.adding = false;
        }
    },
}));

Alpine.data('carousel', () => ({
    scrollBy(dir) {
        const el = this.$refs.track;
        let amount = el.clientWidth * (dir > 0 ? 0.85 : -0.85);
        el.scrollBy({ left: amount, behavior: 'smooth' });
    },
}));

Alpine.data('checkoutTotals', ({ subtotal, discount, shipping }) => ({
    subtotal,
    discount,
    shipping,
    setShipping(price) {
        this.shipping = price;
    },
    total() {
        return Math.round((this.subtotal - this.discount + this.shipping) * 100) / 100;
    },
    money(value) {
        return '£' + Number(value).toFixed(2);
    },
}));

Alpine.plugin(collapse);
Alpine.start();