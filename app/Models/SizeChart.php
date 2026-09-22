<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChart extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_type', 'rows', 'is_active'];

    protected function casts(): array
    {
        return [
            'rows' => 'array',
            'is_active' => 'boolean',
        ];
    }
}