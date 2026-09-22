import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
import Alpine from 'alpinejs';

window.Livewire = Livewire;

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

Alpine.start();

Livewire.start();