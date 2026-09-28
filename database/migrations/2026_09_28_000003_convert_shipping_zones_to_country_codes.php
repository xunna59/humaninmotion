<?php

use App\Models\ShippingMethod;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $map = [
            'United Kingdom' => 'GB',
            'Ireland' => 'IE',
            'France' => 'FR',
            'Germany' => 'DE',
            'Spain' => 'ES',
            'Italy' => 'IT',
            'Netherlands' => 'NL',
            'Belgium' => 'BE',
            'Austria' => 'AT',
            'Portugal' => 'PT',
            'United States' => 'US',
            'Canada' => 'CA',
            'Australia' => 'AU',
            'United Arab Emirates' => 'AE',
        ];

        ShippingMethod::query()->each(function (ShippingMethod $method) use ($map) {
            $codes = [];
            foreach ($method->zones ?? [] as $zone) {
                if (isset($map[$zone])) {
                    $codes[] = $map[$zone];
                }
            }
            $method->zones = $codes;
            $method->save();
        });
    }

    public function down(): void
    {
        $map = [
            'GB' => 'United Kingdom',
            'IE' => 'Ireland',
            'FR' => 'France',
            'DE' => 'Germany',
            'ES' => 'Spain',
            'IT' => 'Italy',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'AT' => 'Austria',
            'PT' => 'Portugal',
            'US' => 'United States',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'AE' => 'United Arab Emirates',
        ];

        ShippingMethod::query()->each(function (ShippingMethod $method) use ($map) {
            $names = [];
            foreach ($method->zones ?? [] as $code) {
                if (isset($map[$code])) {
                    $names[] = $map[$code];
                }
            }
            $method->zones = $names;
            $method->save();
        });
    }
};