<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'sku',
        'price',
        'compare_price',
        'category_id',
        'brand',
        'status',
        'is_featured',
        'is_bestseller',
        'is_new',
        'is_limited',
        'is_exclusive',
        'fit',
        'material',
        'style',
        'views',
        'care_instructions',
        'tags',
        'video_url',
        'meta_title',
        'meta_description',
        'canonical_url',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'is_new' => 'boolean',
            'is_limited' => 'boolean',
            'is_exclusive' => 'boolean',
            'tags' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('position');
    }

    public function activeVariants(): HasMany
    {
        return $this->variants()->where('is_active', true);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class)
            ->withPivot('position')
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->active()->whereNotNull('published_at');
    }

    public function scopeInStock(Builder $query, bool $onlyInStock = true): Builder
    {
        if (! $onlyInStock) {
            return $query;
        }

        return $query->whereHas('variants', function ($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        });
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images->where('position', 1)->first() ?? $this->images->first();
    }

    public function hoverImage(): ?ProductImage
    {
        return $this->images->where('position', 2)->first();
    }

    public function salePrice(): ?string
    {
        if ($this->compare_price > $this->price) {
            return $this->price;
        }

        return null;
    }

    public function isOnSale(): bool
    {
        return $this->compare_price !== null && $this->compare_price > $this->price;
    }

    public function discountPercent(?float $price = null, ?float $compare = null): int
    {
        $compare = $compare ?? $this->compare_price;
        $price = $price ?? $this->price;

        if (! $compare || $compare <= $price) {
            return 0;
        }

        return (int) round((($compare - $price) / $compare) * 100);
    }

    public function totalStock(): int
    {
        return $this->variants->sum('stock');
    }

    public function availableColours(): array
    {
        return $this->variants
            ->filter(fn ($v) => $v->is_active && $v->colour)
            ->pluck('colour')
            ->unique()
            ->values()
            ->all();
    }

    public function availableSizes(?string $colour = null): array
    {
        return $this->variants
            ->filter(fn ($v) => $v->is_active && $v->colour === ($colour ?: $this->variants->first()?->colour))
            ->pluck('size')
            ->unique()
            ->values()
            ->all();
    }

    public function getFullNameAttribute(): string
    {
        return 'Human In Motion ' . $this->name;
    }

    public function defaultVariant(): ?ProductVariant
    {
        return $this->variants->firstWhere('is_active', true) ?? $this->variants->first();
    }

    public function lowestActivePrice(): ?string
    {
        return $this->activeVariants()->min('price') ?: $this->price;
    }
}