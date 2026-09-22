<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'announcement_text', 'value' => 'FREE UK DELIVERY OVER £100', 'group' => 'header'],
            ['key' => 'announcement_enabled', 'value' => true, 'group' => 'header'],
            ['key' => 'free_shipping_threshold', 'value' => 100, 'group' => 'shipping'],
            ['key' => 'company_name', 'value' => 'Human In Motion', 'group' => 'general'],
            ['key' => 'email', 'value' => 'hello@humaninmotion.co.uk', 'group' => 'general'],
            ['key' => 'phone', 'value' => '+44 20 7946 0958', 'group' => 'general'],
            ['key' => 'address', 'value' => 'Human In Motion, 12 Carnaby Street, London, W1F 9PW', 'group' => 'general'],
            ['key' => 'vat_number', 'value' => 'GB123456789', 'group' => 'general'],
            ['key' => 'social', 'value' => [
                'instagram' => 'https://instagram.com/humaninmotion',
                'tiktok' => 'https://tiktok.com/@humaninmotion',
                'x' => 'https://x.com/humaninmotion',
                'youtube' => 'https://youtube.com/@humaninmotion',
            ], 'group' => 'general'],
            ['key' => 'seo_default_title', 'value' => 'Human In Motion | Premium British Menswear', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'value' => 'Premium modern British menswear. Designed for those who move differently.', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], [
                'value' => $setting['value'],
                'group' => $setting['group'],
            ]);
        }
    }
}