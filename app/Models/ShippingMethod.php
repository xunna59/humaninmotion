<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'price',
        'estimate',
        'zones',
        'free_above',
        'sort_order',
        'is_active',
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'free_above' => 'float',
            'zones' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
