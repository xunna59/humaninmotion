<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Collection;

class NavigationService
{
    public function navCategories(): array
    {
        return Category::query()
            ->where('show_in_nav', true)
            ->where('is_active', true)
            ->orderBy('position')
            ->get()
            ->filter(fn (Category $category) => $category->parent_id === null)
            ->mapWithKeys(function (Category $category) {
                return [
                    $category->slug => $category->children
                        ->where('is_active', true)
                        ->map(fn ($child) => [
                            'name' => $child->name,
                            'url' => route('category.show', $child->slug),
                        ])
                        ->values(),
                ];
            })
            ->all();
    }

    public function shopLinks(): array
    {
        return [
            ['name' => 'New In', 'url' => route('new-in')],
            ['name' => 'Best Sellers', 'url' => route('best-sellers')],
            ['name' => 'Clothing', 'url' => route('shop')],
            ['name' => 'Collections', 'url' => route('collections.index')],
            ['name' => 'Sale', 'url' => route('sale')],
        ];
    }

    public function collections(): \Illuminate\Database\Eloquent\Collection
    {
        return Collection::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
    }

    public function footer(): array
    {
        return [
            'shop' => [
                ['name' => 'New In', 'url' => route('new-in')],
                ['name' => 'Clothing', 'url' => route('shop')],
                ['name' => 'Collections', 'url' => route('collections.index')],
                ['name' => 'Best Sellers', 'url' => route('best-sellers')],
                ['name' => 'Sale', 'url' => route('sale')],
            ],
            'help' => [
                ['name' => 'Contact', 'url' => route('page.show', 'contact')],
                ['name' => 'Delivery', 'url' => route('page.show', 'delivery')],
                ['name' => 'Returns', 'url' => route('page.show', 'returns')],
                ['name' => 'Size Guide', 'url' => route('page.show', 'size-guide')],
                ['name' => 'FAQ', 'url' => route('page.show', 'faq')],
            ],
            'about' => [
                ['name' => 'Our Story', 'url' => route('page.show', 'our-story')],
                ['name' => 'Journal', 'url' => route('journal.index')],
                ['name' => 'Careers', 'url' => route('page.show', 'careers')],
                ['name' => 'Stockists', 'url' => route('page.show', 'stockists')],
            ],
            'legal' => [
                ['name' => 'Privacy Policy', 'url' => route('page.show', 'privacy-policy')],
                ['name' => 'Terms & Conditions', 'url' => route('page.show', 'terms-conditions')],
                ['name' => 'Cookie Policy', 'url' => route('page.show', 'cookie-policy')],
                ['name' => 'Accessibility', 'url' => route('page.show', 'accessibility')],
            ],
        ];
    }
}