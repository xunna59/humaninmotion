import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
import collapse from '@alpinejs/collapse';

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

    init({ variants = [], images = [] } = {}) {
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

Alpine.data('checkoutTotals', ({ subtotal, discount, shipping, stripe = false, stripeKey = null, intentUrl = null, csrf = null, initialShippingMethod = 'uk_standard' }) => ({
    subtotal,
    discount,
    shipping,
    stripe,                   // Stripe JS instance (once loaded)
    stripeKey,
    intentUrl,
    csrf,
    initialShippingMethod,
    elements: null,
    paymentElement: null,
    intentId: null,
    redirectConfirmed: false,
    paymentError: null,
    processing: false,
    gateway: stripe ? 'stripe' : 'mock',
    total() {
        return Math.round((this.subtotal - this.discount + this.shipping) * 100) / 100;
    },
    money(value) {
        return '£' + Number(value).toFixed(2);
    },
    async init() {
        if (! this.stripeKey || ! this.intentUrl) {
            return;
        }

        try {
            if (! window.Stripe) {
                await this.loadStripeJs();
            }
            this.stripe = window.Stripe(this.stripeKey);

            // Returned from a 3DS redirect — intent should already be confirmed.
            const redirectSecret = new URLSearchParams(window.location.search).get('payment_intent_client_secret');
            if (redirectSecret) {
                const { paymentIntent } = await this.stripe.retrievePaymentIntent(redirectSecret);
                if (paymentIntent && ['succeeded', 'processing'].includes(paymentIntent.status)) {
                    this.intentId = paymentIntent.id;
                    this.redirectConfirmed = true;
                    history.replaceState(null, '', window.location.pathname);
                    return;
                }
            }

            await this.mountElement(this.initialShippingMethod);
        } catch (err) {
            this.paymentError = err.message || 'Could not start card payment. Please try again.';
        }
    },
    loadStripeJs() {
        return new Promise((resolve, reject) => {
            if (window.Stripe) return resolve();
            const s = document.createElement('script');
            s.src = 'https://js.stripe.com/v3/';
            s.onload = resolve;
            s.onerror = () => reject(new Error('Could not load Stripe.'));
            document.head.appendChild(s);
        });
    },
    async createSecret(shippingCode) {
        const res = await fetch(this.intentUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf },
            body: JSON.stringify({ shipping_method: shippingCode }),
        });
        const data = await res.json();
        if (! res.ok) {
            throw new Error(data.error || 'Could not create a secure payment. Please try again.');
        }
        return data;
    },
    async mountElement(shippingCode) {
        const data = await this.createSecret(shippingCode);
        this.elementClientSecret = data.client_secret;
        this.intentId = data.intent_id;

        if (! this.elements) {
            this.elements = this.stripe.elements({
                clientSecret: this.elementClientSecret,
                appearance: {
                    theme: 'stripe',
                    variables: { colorText: '#1a1a1a' },
                },
            });
            this.paymentElement = this.elements.create('payment', { layout: 'accordion' });
            this.paymentElement.mount('#stripe-payment-element');
        } else {
            this.elements.update({ clientSecret: this.elementClientSecret });
            if (typeof this.elements.fetchUpdates === 'function') {
                await this.elements.fetchUpdates();
            }
        }
    },
    async setShipping(price, code = 'uk_standard') {
        this.shipping = price;
        if (this.gateway === 'stripe' && this.stripe) {
            try {
                await this.mountElement(code);
            } catch (err) {
                this.paymentError = err.message || 'Could not refresh card payment. Please try again.';
            }
        }
    },
    async handleSubmit(event) {
        if (this.gateway !== 'stripe') {
            return; // plain POST to the demo gateway
        }

        event.preventDefault();
        if (this.processing) {
            return;
        }
        this.paymentError = null;

        // Already authorised (returned from a 3DS redirect) — just place the order.
        if (this.redirectConfirmed && this.intentId) {
            this.finishSubmit();
            return;
        }

        this.processing = true;
        const { error, paymentIntent } = await this.stripe.confirmPayment({
            elements: this.elements,
            redirect: 'if_required',
            confirmParams: { return_url: window.location.href },
        });

        if (error) {
            this.processing = false;
            this.paymentError = error.message;
            return;
        }

        // A redirect (e.g. 3DS) is navigating the browser away.
        if (! paymentIntent) {
            this.processing = false;
            return;
        }

        this.intentId = paymentIntent.id;
        this.finishSubmit();
    },
    finishSubmit() {
        const input = this.$el.querySelector('input[name="payment_intent_id"]');
        if (input && this.intentId) {
            input.value = this.intentId;
            this.$el.submit();
        } else {
            this.paymentError = 'Payment could not be verified. Please try again.';
            this.processing = false;
        }
    },
}));

Alpine.plugin(collapse);
Livewire.start();