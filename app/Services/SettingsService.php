<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function __construct(private readonly array $defaults = [])
    {
        $this->defaults = [
            'announcement_text' => 'FREE UK DELIVERY OVER £100',
            'announcement_enabled' => true,
            'free_shipping_threshold' => 100,
            'company_name' => 'Human In Motion',
            'email' => 'hello@humaninmotion.co.uk',
            'phone' => '+44 20 7946 0958',
            'address' => 'Human In Motion, 12 Carnaby Street, London, W1F 9PW',
            'vat_number' => 'GB123456789',
            'social' => [
                'instagram' => 'https://instagram.com/humaninmotion',
                'tiktok' => 'https://tiktok.com/@humaninmotion',
                'x' => 'https://x.com/humaninmotion',
                'youtube' => 'https://youtube.com/@humaninmotion',
            ],
            'seo_default_title' => 'Human In Motion | Premium British Menswear',
            'seo_default_description' => 'Premium modern British menswear. Designed for those who move differently.',
        ];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = Setting::get($key);

        return $value ?? $default ?? ($this->defaults[$key] ?? null);
    }

    public function getGroup(string $group): array
    {
        return Setting::query()->where('group', $group)->pluck('value', 'key')->all();
    }

    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        Setting::set($key, $value, $group);
    }

    public function announcement(): ?string
    {
        if (! $this->get('announcement_enabled', true)) {
            return null;
        }

        return $this->get('announcement_text');
    }

    public function social(): array
    {
        return $this->get('social', $this->defaults['social'] ?? []);
    }
}