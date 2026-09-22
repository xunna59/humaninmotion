<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use Illuminate\Database\Seeder;

class CatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $clothing = Category::updateOrCreate(['slug' => 'clothing'], [
            'name' => 'Clothing',
            'description' => 'Everyday essentials engineered for movement.',
            'position' => 1,
            'is_active' => true,
            'show_in_nav' => true,
        ]);

        $categories = [
            't-shirts' => 'T-Shirts',
            'shirts' => 'Shirts',
            'hoodies' => 'Hoodies',
            'sweatshirts' => 'Sweatshirts',
            'knitwear' => 'Knitwear',
            'jackets' => 'Jackets',
            'coats' => 'Coats',
            'trousers' => 'Trousers',
            'jeans' => 'Jeans',
            'shorts' => 'Shorts',
            'tracksuits' => 'Tracksuits',
            'sets' => 'Sets',
        ];

        foreach ($categories as $slug => $name) {
            Category::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'parent_id' => $clothing->id,
                'is_active' => true,
                'show_in_nav' => false,
            ]);
        }

        $accessories = Category::updateOrCreate(['slug' => 'accessories'], [
            'name' => 'Accessories',
            'description' => 'The finishing details.',
            'position' => 2,
            'is_active' => true,
            'show_in_nav' => true,
        ]);

        foreach (['hats' => 'Hats', 'bags' => 'Bags', 'scarves' => 'Scarves', 'socks' => 'Socks'] as $slug => $name) {
            Category::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'parent_id' => $accessories->id,
                'is_active' => true,
                'show_in_nav' => false,
            ]);
        }

        $collections = [
            ['name' => 'Core Collection', 'slug' => 'core', 'position' => 1, 'is_featured' => true, 'promotional_text' => 'The foundation pieces.', 'cta_text' => 'Shop Core'],
            ['name' => 'Signature Collection', 'slug' => 'signature', 'position' => 2, 'is_featured' => true, 'promotional_text' => 'Statement shapes. Defined proportions.', 'cta_text' => 'Shop Signature'],
            ['name' => 'Essentials', 'slug' => 'essentials', 'position' => 3, 'promotional_text' => 'Wear every day. On rotation.', 'cta_text' => 'Shop Essentials'],
            ['name' => 'Limited Edition', 'slug' => 'limited-edition', 'position' => 4, 'is_featured' => true, 'promotional_text' => 'Small runs. Never restocked.', 'cta_text' => 'Shop Limited'],
            ['name' => 'Seasonal Collection', 'slug' => 'seasonal', 'position' => 5, 'promotional_text' => 'This season\u2019s movement.', 'cta_text' => 'Shop Seasonal'],
            ['name' => 'New Season', 'slug' => 'new-season', 'position' => 6, 'promotional_text' => 'The latest drops.', 'cta_text' => 'Explore New Season'],
        ];

        foreach ($collections as $collection) {
            Collection::updateOrCreate(['slug' => $collection['slug']], $collection + [
                'description' => 'A Human In Motion edit. Designed to be lived in.',
                'is_active' => true,
                'starts_at' => now()->subDays(60),
                'ends_at' => now()->addDays(90),
                'meta_title' => 'Human In Motion | ' . $collection['name'],
            ]);
        }
    }
}