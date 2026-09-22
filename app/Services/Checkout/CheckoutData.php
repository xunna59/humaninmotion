<?php

namespace App\Services\Checkout;

use App\Models\Address;

class CheckoutData
{
    public function __construct(
        public string $email,
        public array $shipping,
        public array $billing,
        public string $shippingMethod = 'uk_standard',
        public ?int $billingAddressId = null,
        public ?string $customerNote = null,
        public ?string $paymentMethod = 'mock',
        public array $paymentPayload = [],
    ) {
    }

    public static function fromRequest(array $input, ?Address $defaultAddress = null): self
    {
        $sameAsShipping = (bool) ($input['billing_same'] ?? false);

        return new self(
            email: $input['email'],
            shipping: [
                'name' => $input['shipping_name'],
                'line_one' => $input['shipping_line_one'],
                'line_two' => $input['shipping_line_two'] ?? null,
                'city' => $input['shipping_city'],
                'county' => $input['shipping_county'] ?? null,
                'postcode' => $input['shipping_postcode'],
                'country' => $input['shipping_country'],
                'phone' => $input['shipping_phone'] ?? null,
            ],
            billing: $sameAsShipping
                ? [
                    'name' => $input['shipping_name'],
                    'line_one' => $input['shipping_line_one'],
                    'line_two' => $input['shipping_line_two'] ?? null,
                    'city' => $input['shipping_city'],
                    'county' => $input['shipping_county'] ?? null,
                    'postcode' => $input['shipping_postcode'],
                    'country' => $input['shipping_country'],
                    'phone' => $input['shipping_phone'] ?? null,
                ]
                : [
                    'name' => $input['billing_name'],
                    'line_one' => $input['billing_line_one'],
                    'line_two' => $input['billing_line_two'] ?? null,
                    'city' => $input['billing_city'],
                    'county' => $input['billing_county'] ?? null,
                    'postcode' => $input['billing_postcode'],
                    'country' => $input['billing_country'],
                    'phone' => $input['billing_phone'] ?? null,
                ],
            shippingMethod: $input['shipping_method'] ?? 'uk_standard',
            billingAddressId: isset($input['billingAddressId']) && $input['billingAddressId']
                ? (int) $input['billingAddressId']
                : null,
            customerNote: $input['customer_note'] ?? null,
            paymentMethod: $input['payment_method'] ?? 'mock',
            paymentPayload: $input['payment_payload'] ?? [],
        );
    }

    public function toArrayForLog(): array
    {
        return [
            'email' => $this->email,
            'shipping_method' => $this->shippingMethod,
            'country' => $this->shipping['country'] ?? null,
        ];
    }
}