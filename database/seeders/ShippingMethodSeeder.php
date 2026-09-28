<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use App\Services\SettingsService;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $threshold = (float) app(SettingsService::class)->get('free_shipping_threshold', 100);

        $methods = [
            [
                'code' => 'uk_standard',
                'name' => 'UK Standard',
                'price' => 3.95,
                'estimate' => '2–4 working days',
                'zones' => ['GB'],
                'free_above' => $threshold,
                'sort_order' => 1,
            ],
            [
                'code' => 'uk_express',
                'name' => 'UK Express',
                'price' => 6.95,
                'estimate' => '1–2 working days',
                'zones' => ['GB'],
                'free_above' => null,
                'sort_order' => 2,
            ],
            [
                'code' => 'europe',
                'name' => 'Europe Standard',
                'price' => 12.00,
                'estimate' => '5–9 working days',
                'zones' => ['IE', 'FR', 'DE', 'ES', 'IT', 'NL', 'BE', 'AT', 'PT'],
                'free_above' => 200.0,
                'sort_order' => 3,
            ],
            [
                'code' => 'intl',
                'name' => 'International',
                'price' => 18.00,
                'estimate' => '7–14 working days',
                'zones' => ['US', 'CA', 'AU', 'AE'],
                'free_above' => 250.0,
                'sort_order' => 4,
            ],
        ];

        foreach ($methods as $method) {
            ShippingMethod::updateOrCreate(['code' => $method['code']], $method + ['is_active' => true]);
        }
    }
}
