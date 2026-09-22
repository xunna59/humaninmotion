<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    const APPLIES_ALL = 'all';
    const APPLIES_PRODUCTS = 'products';
    const APPLIES_CATEGORIES = 'categories';
    const APPLIES_COLLECTIONS = 'collections';

    protected $fillable = [
        'name',
        'type',
        'value',
        'applies_to',
        'applies_ids',
        'badge',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'applies_ids' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function appliesToProduct(Product $product): bool
    {
        if ($this->applies_to === self::APPLIES_ALL) {
            return true;
        }
        if ($this->applies_to === self::APPLIES_PRODUCTS) {
            return in_array($product->id, $this->applies_ids ?? []);
        }
        if ($this->applies_to === self::APPLIES_CATEGORIES) {
            return in_array($product->category_id, $this->applies_ids ?? []);
        }
        if ($this->applies_to === self::APPLIES_COLLECTIONS) {
            return $product->collections()->whereIn('collections.id', $this->applies_ids ?? [])->exists();
        }

        return false;
    }

    public function isRunning(): bool
    {
        $now = now();
        if (! $this->is_active) {
            return false;
        }
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }
        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }
}