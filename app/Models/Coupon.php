<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';
    const TYPE_FREE_SHIPPING = 'free_shipping';

    const APPLIES_ALL = 'all';
    const APPLIES_PRODUCTS = 'products';
    const APPLIES_CATEGORIES = 'categories';
    const APPLIES_COLLECTIONS = 'collections';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'max_discount',
        'applies_to',
        'applies_ids',
        'usage_limit',
        'per_customer_limit',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_spend' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'applies_ids' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function usageCount(): int
    {
        return $this->usages()->count();
    }

    public function isValid(int $usageLimit = null, float $subtotal = null): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }
        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }
        if ($this->usage_limit !== null && $this->usageCount() >= $this->usage_limit) {
            return false;
        }
        if ($this->min_spend !== null && $subtotal !== null && $subtotal < (float) $this->min_spend) {
            return false;
        }

        return true;
    }
}