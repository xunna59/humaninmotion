<?php

namespace App\Services\Content;

use App\Models\Collection;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Services\Catalogue\ProductQuery;

class HomepageService
{
    public function sections(): \Illuminate\Support\Collection
    {
        return HomepageSection::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (HomepageSection $section) => [
                'section' => $section,
                'products' => $this->productsFor($section),
                'collections' => $this->collectionsFor($section),
                'categories' => $this->categoriesFor($section),
            ]);
    }

    public function productsFor(HomepageSection $section): \Illuminate\Database\Eloquent\Collection
    {
        $tag = $section->content['tag'] ?? null;
        $ids = $section->content['product_ids'] ?? null;

        if ($ids) {
            return Product::query()->active()->whereIn('id', $ids)->get();
        }

        if (! $tag) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        $query = app(ProductQuery::class)->withFilters(match ($tag) {
            'new' => ['new' => true],
            'bestsellers' => ['bestseller' => true],
            'featured' => ['featured' => true],
            'limited' => ['limited' => true],
            'sale' => ['sale' => true],
            default => [],
        });

        return $query->get(match ($tag) {
            'new' => 'newest',
            default => 'featured',
        })->take(10);
    }

    public function collectionsFor(HomepageSection $section): \Illuminate\Support\Collection
    {
        $slugs = $section->content['collections'] ?? [];

        if (empty($slugs)) {
            $slugs = ['signature', 'limited-edition', 'essentials'];
        }

        return Collection::query()
            ->whereIn('slug', $slugs)
            ->orderByRaw('position asc')
            ->get();
    }

    public function categoriesFor(HomepageSection $section): \Illuminate\Support\Collection
    {
        return \App\Models\Category::query()
            ->where('parent_id', null)
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
    }
}