<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JournalArticle extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';

    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'cover_image',
        'gallery',
        'content',
        'author',
        'category',
        'status',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED && $this->published_at !== null;
    }

    public function coverImageUrl(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        return $this->assetUrl($this->cover_image);
    }

    public function galleryUrls(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn (string $path) => $this->assetUrl($path))
            ->values()
            ->all();
    }

    private function assetUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return '/storage/'.ltrim($path, '/');
    }
}
