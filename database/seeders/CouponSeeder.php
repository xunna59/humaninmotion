<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['code' => 'WELCOME10', 'type' => 'percentage', 'value' => 10, 'min_spend' => null, 'max_discount' => 25, 'usage_limit' => 1000, 'per_customer_limit' => 1, 'ends_at' => now()->addYear()],
            ['code' => 'SAVE20', 'type' => 'percentage', 'value' => 20, 'min_spend' => 80, 'max_discount' => 50, 'usage_limit' => 500, 'per_customer_limit' => 2, 'ends_at' => now()->addMonths(6)],
            ['code' => 'FREESHIP', 'type' => 'free_shipping', 'value' => 0, 'min_spend' => 50, 'usage_limit' => 500, 'per_customer_limit' => 1, 'ends_at' => now()->addMonths(3)],
            ['code' => 'LIMITED15', 'type' => 'percentage', 'value' => 15, 'min_spend' => 100, 'max_discount' => 40, 'usage_limit' => 200, 'per_customer_limit' => 1, 'ends_at' => now()->addMonths(2)],
            ['code' => 'EXCLVC5', 'type' => 'percentage', 'value' => 5, 'min_spend' => 40, 'max_discount' => 15, 'usage_limit' => 2000, 'per_customer_limit' => 3, 'ends_at' => now()->addYear()],
        ];

        foreach ($coupons as $coupon) {
            $coupon['starts_at'] = now()->subDay();
            $coupon['is_active'] = true;
            $coupon['applies_to'] = 'all';
            $coupon['applies_ids'] = null;
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}