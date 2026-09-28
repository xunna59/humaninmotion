<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    public const TEMPLATES = [
        'contact' => 'shop.contact',
    ];

    protected $fillable = ['title', 'slug', 'content', 'meta_title', 'meta_description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public static function templateFor(string $slug): ?string
    {
        return self::TEMPLATES[$slug] ?? null;
    }

    public static function templateSlugs(): array
    {
        return array_keys(self::TEMPLATES);
    }
}
