<?php

namespace App\Services\Catalogue;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery
{
    protected Builder $query;

    protected array $filters = [];

    public function base(): Builder
    {
        return Product::query()
            ->active()
            ->whereNotNull('published_at')
            ->with([
                'category',
                'collections',
                'variants' => fn ($q) => $q->where('is_active', true),
                'images',
            ]);
    }

    public function build(): Builder
    {
        $q = $this->base();

        $this->applyStatusFlags($q);
        $this->applySearch($q);
        $this->applyFilters($q);

        return $q;
    }

    public function paginate(int $perPage, string $sort = 'featured', int $page = null): LengthAwarePaginator
    {
        return $this->orderBySort($this->build(), $sort)->paginate($perPage, ['*'], 'page', $page);
    }

    public function get(string $sort = 'featured'): \Illuminate\Database\Eloquent\Collection
    {
        return $this->orderBySort($this->build(), $sort)->get();
    }

    protected function orderBySort(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'newest' => $query->orderByDesc('published_at'),
            'best_selling' => $query->orderByDesc('is_bestseller')->orderByDesc('published_at'),
            'price_low_high' => $query->orderBy('price'),
            'price_high_low' => $query->orderByDesc('price'),
            'recommended' => $query->orderByDesc('is_bestseller')->orderByDesc('is_limited')->orderByDesc('published_at'),
            default => $query->orderByDesc('is_featured')->orderByDesc('published_at'),
        };
    }

    protected function applyStatusFlags(Builder $query): Builder
    {
        if ($this->filters['new'] ?? false) {
            $query->where('is_new', true);
        }
        if ($this->filters['sale'] ?? false) {
            $query->whereNotNull('compare_price')->whereColumn('compare_price', '>', 'price');
        }
        if ($this->filters['bestseller'] ?? false) {
            $query->where('is_bestseller', true);
        }
        if ($this->filters['featured'] ?? false) {
            $query->where('is_featured', true);
        }
        if ($this->filters['limited'] ?? false) {
            $query->where('is_limited', true);
        }

        return $query;
    }

    protected function applySearch(Builder $query): Builder
    {
        $term = trim($this->filters['term'] ?? '');
        if ($term === '') {
            return $query;
        }

        $query->where(function (Builder $q) use ($term) {
            $q->where('products.name', 'like', "%{$term}%")
                ->orWhere('products.sku', 'like', "%{$term}%")
                ->orWhere('products.short_description', 'like', "%{$term}%")
                ->orWhere('products.brand', 'like', "%{$term}%")
                ->orWhere('products.tags', 'like', "%{$term}%")
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$term}%"))
                ->orWhereHas('collections', fn ($c) => $c->where('name', 'like', "%{$term}%"));
        });

        return $query;
    }

    protected function applyFilters(Builder $query): Builder
    {
        $query = $this->applyCategory($query);
        $query = $this->applyCollection($query);
        $query = $this->applyOptions($query);
        $query = $this->applyPrice($query);
        $query = $this->applyAvailability($query);
        $query = $this->applyAttributes($query);

        return $query;
    }

    protected function applyCategory(Builder $query): Builder
    {
        $slug = $this->filters['category'] ?? null;
        if (! $slug) {
            return $query;
        }

        return $query->whereHas('category', function ($q) use ($slug) {
            $q->where('slug', $slug)
                ->orWhereHas('parent', fn ($p) => $p->where('slug', $slug));
        });
    }

    protected function applyCollection(Builder $query): Builder
    {
        $slug = $this->filters['collection'] ?? null;
        if (! $slug) {
            return $query;
        }

        return $query->whereHas('collections', fn ($q) => $q->where('collections.slug', $slug));
    }

    protected function applyOptions(Builder $query): Builder
    {
        $sizes = $this->filters['sizes'] ?? [];
        $colours = $this->filters['colours'] ?? [];
        if ($sizes) {
            $query->whereHas('activeVariants', fn ($q) => $q->whereIn('size', $sizes));
        }
        if ($colours) {
            $query->whereHas('activeVariants', fn ($q) => $q->whereIn('colour', $colours));
        }

        return $query;
    }

    protected function applyPrice(Builder $query): Builder
    {
        $min = $this->filters['price_min'] ?? null;
        $max = $this->filters['price_max'] ?? null;
        if ($min !== null) {
            $query->where('products.price', '>=', (float) $min);
        }
        if ($max !== null) {
            $query->where('products.price', '<=', (float) $max);
        }

        return $query;
    }

    protected function applyAvailability(Builder $query): Builder
    {
        if (($this->filters['in_stock'] ?? false)) {
            $query->inStock();
        }

        return $query;
    }

    protected function applyAttributes(Builder $query): Builder
    {
        if ($this->filters['fit'] ?? null) {
            $query->where('products.fit', $this->filters['fit']);
        }
        if ($this->filters['material'] ?? null) {
            $query->where('products.material', 'like', "%{$this->filters['material']}%");
        }
        if ($this->filters['style'] ?? null) {
            $query->where('products.style', $this->filters['style']);
        }
        if ($this->filters['type'] ?? null) {
            $query->where('products.style', $this->filters['type']);
        }

        return $query;
    }

    protected static function facetSizes(Builder $query): array
    {
        return $query->active()->whereHas('activeVariants', fn ($v) => $v->where('stock', '>', 0))
            ->leftJoin('product_variants', function ($join) {
                $join->on('product_variants.product_id', '=', 'products.id')
                    ->where('product_variants.is_active', true);
            })
            ->whereNotNull('product_variants.size')
            ->distinct()
            ->pluck('product_variants.size')
            ->sortBy(fn ($s) => ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6][$s] ?? 99)
            ->values()
            ->all();
    }

    public static function availableColours(Builder $query): array
    {
        return $query->active()
            ->leftJoin('product_variants', function ($join) {
                $join->on('product_variants.product_id', '=', 'products.id')
                    ->where('product_variants.is_active', true);
            })
            ->whereNotNull('product_variants.colour')
            ->distinct()
            ->pluck('product_variants.colour')
            ->sort()
            ->values()
            ->all();
    }

    public function facetsFor(array $productIds): array
    {
        $scope = Product::query()->whereIn('products.id', $productIds);

        return [
            'colours' => self::availableColours(clone $scope),
            'sizes' => $this->facetSizes(clone $scope),
        ];
    }

    public function withFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}