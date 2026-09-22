<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutData;
use App\Services\Checkout\CheckoutService;
use App\Services\Payments\PaymentManager;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $cart = app(CartService::class)->current();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('bag');
        }

        $totals = app(CheckoutService::class)->totals($cart);
        $user = $request->user();

        $defaultShipping = $user?->addresses()->where('type', 'shipping')->where('is_default', true)->first()
            ?? $user?->addresses()->where('type', 'shipping')->first();

        return view('checkout.index', [
            'cart' => $cart,
            'totals' => $totals,
            'shippingMethods' => app(ShippingService::class)->methods(),
            'defaultShipping' => $defaultShipping,
            'gatewayLabel' => app(PaymentManager::class)->gateway()->providerName() === 'mock'
                ? 'Payment is simulated in this demo. No real card data is taken.'
                : null,
            'title' => 'Checkout | Human In Motion',
        ]);
    }

    public function place(Request $request): RedirectResponse
    {
        $cart = app(CartService::class)->current();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('bag');
        }

        $data = $request->validate([
            'email' => ['required', 'email'],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_line_one' => ['required', 'string', 'max:255'],
            'shipping_line_two' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_county' => ['nullable', 'string', 'max:255'],
            'shipping_postcode' => ['required', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:100'],
            'shipping_phone' => ['nullable', 'string', 'max:32'],
            'billing_same' => ['nullable', 'boolean'],
            'billing_name' => ['nullable', 'required_if:billing_same,false', 'string', 'max:255'],
            'billing_line_one' => ['nullable', 'required_if:billing_same,false', 'string', 'max:255'],
            'billing_line_two' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['nullable', 'required_if:billing_same,false', 'string', 'max:255'],
            'billing_county' => ['nullable', 'string', 'max:255'],
            'billing_postcode' => ['nullable', 'required_if:billing_same,false', 'string', 'max:20'],
            'billing_country' => ['nullable', 'required_if:billing_same,false', 'string', 'max:100'],
            'billing_phone' => ['nullable', 'string', 'max:32'],
            'shipping_method' => ['required', 'string', 'in:uk_standard,uk_express,europe,intl'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'string', 'in:mock'],
        ]);

        try {
            $checkoutData = CheckoutData::fromRequest($data);
            $order = app(CheckoutService::class)->place(
                $cart,
                $checkoutData,
                $request->user()?->id,
            );
        } catch (\RuntimeException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()]);
        }

        return redirect()->route('checkout.confirmation', $order);
    }

    public function confirmation(Request $request, Order $order): View
    {
        $order->load(['items.product', 'shippingAddress']);

        return view('checkout.confirmation', [
            'order' => $order,
            'title' => 'Order confirmed | Human In Motion',
        ]);
    }
}