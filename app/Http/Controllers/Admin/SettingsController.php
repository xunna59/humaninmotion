<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(SettingsService $settings): View
    {
        return view('admin.settings.index', [
            'settings' => $settings,
            'groups' => [
                'announcement_text' => 'Announcement bar text',
                'announcement_enabled' => 'Announcement bar enabled',
                'free_shipping_threshold' => 'Free shipping threshold (£)',
                'company_name' => 'Company name',
                'email' => 'Contact email',
                'phone' => 'Phone',
                'address' => 'Address',
                'vat_number' => 'VAT number',
                'seo_default_title' => 'Default SEO title',
                'seo_default_description' => 'Default SEO description',
            ],
            'socials' => ['instagram', 'tiktok', 'x', 'youtube'],
            'title' => 'Settings',
        ]);
    }

    public function update(Request $request, SettingsService $settings): RedirectResponse
    {
        $data = $request->validate([
            'announcement_text' => ['nullable', 'string', 'max:255'],
            'announcement_enabled' => ['nullable', 'boolean'],
            'free_shipping_threshold' => ['nullable', 'numeric', 'min:0'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'address' => ['nullable', 'string', 'max:500'],
            'vat_number' => ['nullable', 'string', 'max:64'],
            'seo_default_title' => ['nullable', 'string', 'max:255'],
            'seo_default_description' => ['nullable', 'string', 'max:1000'],
            'social' => ['nullable', 'array'],
            'social.*' => ['nullable', 'url', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            if ($key !== 'social') {
                $settings->set($key, $value);
            }
        }

        $settings->set('social', $data['social'] ?? [], 'general');

        return back()->with('status', 'Settings saved.');
    }
}