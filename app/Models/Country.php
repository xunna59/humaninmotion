<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    protected function casts(): array
    {
        return ['code' => 'string'];
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
