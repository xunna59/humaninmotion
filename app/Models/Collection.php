<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'hero_image',
        'mobile_hero_image',
        'campaign_video',
        'promotional_text',
        'cta_text',
        'cta_url',
        'meta_title',
        'meta_description',
        'starts_at',
        'ends_at',
        'is_featured',
        'is_active',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('position')
            ->withTimestamps()
            ->orderBy('collection_product.position');
    }

    public function activeProducts(): BelongsToMany
    {
        return $this->products()->where('products.status', 'active');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}