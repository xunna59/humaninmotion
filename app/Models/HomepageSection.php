<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    const TYPE_HERO = 'hero';
    const TYPE_IMAGE_BANNER = 'image_banner';
    const TYPE_PRODUCT_CAROUSEL = 'product_carousel';
    const TYPE_PRODUCT_GRID = 'product_grid';
    const TYPE_COLLECTION_TILES = 'collection_tiles';
    const TYPE_EDITORIAL = 'editorial';
    const TYPE_PROMOTIONAL_BANNER = 'promotional_banner';
    const TYPE_CATEGORY_GRID = 'category_grid';
    const TYPE_NEWSLETTER = 'newsletter';
    const TYPE_CUSTOM = 'custom';

    protected $fillable = [
        'type',
        'title',
        'description',
        'image_desktop',
        'image_mobile',
        'video_url',
        'button_text',
        'button_url',
        'button_two_text',
        'button_two_url',
        'text_position',
        'overlay_strength',
        'content',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'overlay_strength' => 'integer',
            'content' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function alignmentClass(): string
    {
        return match ($this->text_position) {
            'center' => 'items-center text-center',
            'right' => 'items-end text-right',
            default => 'items-start text-left',
        };
    }
}