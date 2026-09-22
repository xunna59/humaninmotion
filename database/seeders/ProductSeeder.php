<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private array $tops = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    private array $bottoms = ['28', '30', '32', '34', '36'];
    private array $oneSize = ['One Size'];

    public function run(): void
    {
        $bestseller = fn (array $p) => $p + ['is_bestseller' => true];
        $new = fn (array $p) => $p + ['is_new' => true];

        $products = [
            // ── T-SHIRTS ─────────────────────────────────────────────
            $bestseller([
                'name' => 'Essential Heavyweight T-Shirt', 'slug' => 'essential-heavyweight-t-shirt',
                'price' => 35, 'category' => 't-shirts',
                'fit' => 'Regular', 'material' => '220gsm organic cotton jersey', 'style' => 'Essential',
                'description' => 'A heavyweight staple cut from 220gsm organic cotton jersey. Structured shoulders, a clean ribbed collar and a fit that holds its shape wear after wear.',
                'care' => 'Machine wash cold, inside out. Do not tumble dry.',
                'colours' => ['black' => 26, 'white' => 22, 'stone' => 14],
            ]),
            $new([
                'name' => 'Signature Oversized T-Shirt', 'slug' => 'signature-oversized-t-shirt',
                'price' => 42, 'category' => 't-shirts',
                'fit' => 'Oversized', 'material' => '240gsm cotton', 'style' => 'Statement',
                'description' => 'The Signature T-Shirt in a dropped-shoulder oversized cut. A heavier billowing silhouette with a tonal wordmark at the chest.',
                'colours' => ['charcoal' => 20, 'stone' => 12, 'navy' => 18],
            ]),
            ([
                'name' => 'Box Fit Logo Tee', 'slug' => 'box-fit-logo-tee',
                'price' => 38, 'category' => 't-shirts', 'is_limited' => true,
                'fit' => 'Boxy', 'material' => '200gsm combed cotton', 'style' => 'Essential',
                'description' => 'A boxy first-drop logo tee. Ribbed collar, dropped tail and a small embroidered marker at the hem.',
                'colours' => ['white' => 8, 'black' => 10],
            ]),
            ([
                'name' => 'Long Sleeve Crew Tee', 'slug' => 'long-sleeve-crew-tee',
                'price' => 45, 'category' => 't-shirts',
                'fit' => 'Regular', 'material' => 'Cotton blend', 'style' => 'Essential',
                'description' => 'Long sleeve crew with a thicker collar roll. Built for layering through the colder months.',
                'colours' => ['black' => 16, 'grey' => 6],
            ]),

            // ── SHIRTS ───────────────────────────────────────────────
            ([
                'name' => 'Oxford Overshirt', 'slug' => 'oxford-overshirt',
                'price' => 75, 'category' => 'shirts',
                'fit' => 'Relaxed', 'material' => 'Brushed oxford cotton', 'style' => 'Layered',
                'description' => 'An oxford overshirt with a relaxed shape. Worn open or buttoned, over a tee or alone.',
                'colours' => ['stone' => 12, 'white' => 8, 'navy' => 10],
            ]),
            ([
                'name' => 'Checked Flannel Shirt', 'slug' => 'checked-flannel-shirt',
                'price' => 70, 'category' => 'shirts',
                'fit' => 'Regular', 'material' => 'Brushed flannel', 'style' => 'Print',
                'description' => 'Heavyweight flannel in a tonal check. Garment-dyed for a broken-in finish.',
                'colours' => ['burgundy' => 14, 'charcoal' => 10],
            ]),

            // ── HOODIES ──────────────────────────────────────────────
            $bestseller($new([
                'name' => 'Signature Oversized Hoodie', 'slug' => 'signature-oversized-hoodie',
                'price' => 85, 'category' => 'hoodies',
                'fit' => 'Oversized', 'material' => '450gsm brushed fleece', 'style' => 'Statement',
                'description' => 'The signature oversized hoodie. 450gsm loopback fleece, double-lined hood and dropped shoulders for a confident billowing shape.',
                'care' => 'Wash inside out at 30°. Reshape while damp.',
                'colours' => ['charcoal' => 24, 'black' => 26, 'stone' => 10],
            ])),
            $bestseller([
                'name' => 'Essential Heavyweight Hoodie', 'slug' => 'essential-heavyweight-hoodie',
                'price' => 75, 'category' => 'hoodies',
                'fit' => 'Regular', 'material' => '400gsm fleece', 'style' => 'Essential',
                'description' => 'The heavyweight essential. Clean lines, taped neckline and enough weight to feel substantial.',
                'colours' => ['black' => 30, 'navy' => 18],
            ]),
            ([
                'name' => 'BoxFit Fleece Hoodie', 'slug' => 'boxfit-fleece-hoodie',
                'price' => 80, 'category' => 'hoodies', 'is_limited' => true,
                'fit' => 'Boxy', 'material' => '380gsm organic fleece', 'style' => 'Statement',
                'description' => 'A short-run boxfit fleece hoodie. Cropped hem, heavier cuff and an oversized hood.',
                'colours' => ['grey' => 6],
            ]),
            ([
                'name' => 'Half Zip Technical Hoodie', 'slug' => 'half-zip-technical-hoodie',
                'price' => 95, 'category' => 'hoodies', 'is_exclusive' => true,
                'fit' => 'Athletic', 'material' => 'Technical mélange fleece', 'style' => 'Performance',
                'description' => 'Half-zip hoodie in technical mélange fleece with thumb-loop cuffs. Made for transition days.',
                'colours' => ['olive' => 9, 'black' => 12],
            ]),

            // ── SWEATSHIRTS ──────────────────────────────────────────
            ([
                'name' => 'Essential Fleece Crew', 'slug' => 'essential-fleece-crew',
                'price' => 60, 'category' => 'sweatshirts',
                'fit' => 'Regular', 'material' => '360gsm brushed fleece', 'style' => 'Essential',
                'description' => 'A clean crew-neck fleece. Garment-dyed, ribbed cuffs and a double-stitched hem.',
                'colours' => ['black' => 20, 'white' => 12],
            ]),
            $new([
                'name' => 'Oversized Crew Sweat', 'slug' => 'oversized-crew-sweat',
                'price' => 65, 'category' => 'sweatshirts',
                'fit' => 'Oversized', 'material' => '380gsm loopback', 'style' => 'Statement',
                'description' => 'Loopback crew in a relaxed oversized cut with an exaggerated collar.',
                'colours' => ['stone' => 14, 'charcoal' => 12],
            ]),

            // ── KNITWEAR ─────────────────────────────────────────────
            ([
                'name' => 'Chunky Rib Knit', 'slug' => 'chunky-rib-knit',
                'price' => 95, 'category' => 'knitwear',
                'fit' => 'Relaxed', 'material' => 'Cotton knit', 'style' => 'Elevated',
                'description' => 'A chunky rib knit with pronounced texture and a pressed collar.',
                'colours' => ['charcoal' => 8, 'black' => 10],
            ]),
            ([
                'name' => 'Merino Crew Neck', 'slug' => 'merino-crew-neck',
                'price' => 110, 'compare_price' => 130, 'category' => 'knitwear',
                'fit' => 'Regular', 'material' => 'Extra-fine merino wool', 'style' => 'Elevated',
                'description' => 'Extra-fine merino with temperature control. Lightweight, breathable and made to travel.',
                'colours' => ['black' => 6, 'sand' => 9],
            ]),

            // ── JACKETS ──────────────────────────────────────────────
            ([
                'name' => 'Varsity Bomber Jacket', 'slug' => 'varsity-bomber-jacket',
                'price' => 140, 'category' => 'jackets', 'is_limited' => true,
                'fit' => 'Regular', 'material' => 'Ribbed nylon / wool sleeves', 'style' => 'Statement',
                'description' => 'A short-run varsity bomber. Satin body, wool-mix sleeves and embroidered detailing at the chest.',
                'colours' => ['black' => 8],
            ]),
            $new([
                'name' => 'Padded Overshirt Jacket', 'slug' => 'padded-overshirt-jacket',
                'price' => 120, 'category' => 'jackets',
                'fit' => 'Relaxed', 'material' => 'Recycled quilted shell', 'style' => 'Layered',
                'description' => 'Quilted paddling in an overshirt shape. A weather-ready layer for the everyday.',
                'colours' => ['olive' => 10, 'black' => 14],
            ]),
            ([
                'name' => 'Rain Shell Jacket', 'slug' => 'rain-shell-jacket',
                'price' => 130, 'category' => 'jackets',
                'fit' => 'Athletic', 'material' => 'Waterproof recycled shell', 'style' => 'Performance',
                'description' => 'A packable waterproof shell with taped seams and a stowable hood.',
                'colours' => ['black' => 11],
            ]),

            // ── COATS ────────────────────────────────────────────────
            ([
                'name' => 'Wool Blend Overcoat', 'slug' => 'wool-blend-overcoat',
                'price' => 220, 'category' => 'coats',
                'fit' => 'Tailored', 'material' => 'Wool blend', 'style' => 'Elevated',
                'description' => 'A single-breasted overcoat in a wool blend with a structured shoulder.',
                'colours' => ['charcoal' => 6],
            ]),
            ([
                'name' => 'Utility Field Coat', 'slug' => 'utility-field-coat',
                'price' => 190, 'category' => 'coats',
                'fit' => 'Relaxed', 'material' => 'Waxed cotton', 'style' => 'Outdoor',
                'description' => 'Four-pocket field coat in waxed cotton. Built to be thrown on and lived in.',
                'colours' => ['stone' => 7, 'navy' => 6],
            ]),

            // ── TROUSERS ─────────────────────────────────────────────
            $bestseller([
                'name' => 'Relaxed Cargo Trouser', 'slug' => 'relaxed-cargo-trouser',
                'price' => 75, 'category' => 'trousers',
                'fit' => 'Relaxed', 'material' => 'Cotton twill', 'style' => 'Everyday',
                'description' => 'Straight-leg cargo trouser with bellowed side pockets and a drawcord waist.',
                'colours' => ['black' => 24, 'olive' => 18, 'stone' => 12],
            ]),
            ([
                'name' => 'Tailored Straight Trouser', 'slug' => 'tailored-straight-trouser',
                'price' => 85, 'category' => 'trousers',
                'fit' => 'Tailored', 'material' => 'Stretch suiting', 'style' => 'Elevated',
                'description' => 'A sharp straight-leg trouser in performance suiting with a flex waistband.',
                'colours' => ['charcoal' => 16, 'navy' => 14],
            ]),
            $new([
                'name' => 'Technical Tapered Trouser', 'slug' => 'technical-tapered-trouser',
                'price' => 90, 'category' => 'trousers',
                'fit' => 'Tapered', 'material' => 'Technical stretch fabric', 'style' => 'Performance',
                'description' => 'Water-repellent tapered trouser with articulated knees and zip-secured pockets.',
                'colours' => ['black' => 12],
            ]),

            // ── JEANS ────────────────────────────────────────────────
            ([
                'name' => 'Slim Stretch Denim', 'slug' => 'slim-stretch-denim',
                'price' => 80, 'category' => 'jeans',
                'fit' => 'Slim', 'material' => 'Elastane denim', 'style' => 'Essential',
                'description' => 'Slim-leg denim with stretch recovery and a mid-rise fit.',
                'colours' => ['navy' => 15, 'black' => 16],
            ]),
            ([
                'name' => 'Relaxed Wide Denim', 'slug' => 'relaxed-wide-denim',
                'price' => 84, 'compare_price' => 95, 'category' => 'jeans',
                'fit' => 'Relaxed', 'material' => 'Heavyweight denim', 'style' => 'Statement',
                'description' => 'Wide-leg denim with a clean drape and rope-stitched seams.',
                'colours' => ['stone' => 12],
            ]),

            // ── SHORTS ───────────────────────────────────────────────
            ([
                'name' => 'Cargo Short', 'slug' => 'cargo-short',
                'price' => 55, 'category' => 'shorts',
                'fit' => 'Regular', 'material' => 'Cotton twill', 'style' => 'Everyday',
                'description' => 'Above-knee cargo short with flap pockets and an adjustable waist.',
                'colours' => ['stone' => 18, 'olive' => 10],
            ]),

            // ── TRACKSUITS ───────────────────────────────────────────
            ([
                'name' => 'Essential Track Jacket', 'slug' => 'essential-track-jacket',
                'price' => 70, 'category' => 'tracksuits',
                'fit' => 'Relaxed', 'material' => 'Stretch tricot', 'style' => 'Essential',
                'description' => 'Retro-inspired track jacket in a smooth stretch tricot.',
                'colours' => ['black' => 16],
            ]),
            ([
                'name' => 'Essential Track Pant', 'slug' => 'essential-track-pant',
                'price' => 60, 'category' => 'tracksuits',
                'fit' => 'Relaxed', 'material' => 'Stretch tricot', 'style' => 'Essential',
                'description' => 'Matching track pant with tapered leg and zip pockets.',
                'colours' => ['black' => 18],
            ]),
            ([
                'name' => 'Full Zip Track Set', 'slug' => 'full-zip-track-set',
                'price' => 110, 'category' => 'sets',
                'fit' => 'Relaxed', 'material' => 'Stretch tricot', 'style' => 'Essential',
                'description' => 'Complete full-zip set. Jacket and matching pant, sold together.',
                'colours' => ['charcoal' => 8],
            ]),

            // ── ACCESSORIES ──────────────────────────────────────────
            ([
                'name' => 'Structured Cap', 'slug' => 'structured-cap',
                'price' => 28, 'category' => 'hats',
                'fit' => 'One Size', 'material' => 'Cotton twill', 'style' => 'Everyday',
                'description' => 'Six-panel structured cap with an embroidered marker.',
                'colours' => ['black' => 25, 'stone' => 15],
            ]),
            ([
                'name' => 'Ribbed Beanie', 'slug' => 'ribbed-beanie',
                'price' => 25, 'category' => 'hats',
                'fit' => 'One Size', 'material' => 'Acrylic knit', 'style' => 'Essential',
                'description' => 'A deep ribbed beanie in a brushed knit.',
                'colours' => ['charcoal' => 20, 'navy' => 12],
            ]),
            ([
                'name' => 'Canvas Utility Bag', 'slug' => 'canvas-utility-bag',
                'price' => 45, 'category' => 'bags',
                'fit' => 'One Size', 'material' => 'Waxed canvas', 'style' => 'Everyday',
                'description' => 'Waxed canvas utility bag with a zip main compartment and leather trim.',
                'colours' => ['black' => 12, 'olive' => 9],
            ]),
            ([
                'name' => 'Crew Socks 3 Pack', 'slug' => 'crew-socks-3-pack',
                'price' => 15, 'category' => 'socks',
                'fit' => 'One Size', 'material' => 'Cotton blend', 'style' => 'Essential',
                'description' => 'Three pairs of mid-calf crew socks in a tonal mix.',
                'colours' => ['black' => 40, 'white' => 30],
            ]),
        ];

        foreach ($products as $data) {
            $this->createProduct($data);
        }
    }

    private function createProduct(array $data): void
    {
        $category = \App\Models\Category::query()->where('slug', $data['category'])->firstOrFail();

        $product = Product::updateOrCreate(['slug' => $data['slug']], [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'short_description' => $data['description'],
            'description' => $data['description'],
            'sku' => strtoupper('HIM-' . str_replace('-', '', $data['slug'])),
            'price' => $data['price'],
            'compare_price' => $data['compare_price'] ?? null,
            'category_id' => $category->id,
            'brand' => 'Human In Motion',
            'status' => 'active',
            'is_featured' => in_array($data['slug'], ['signature-oversized-hoodie', 'relaxed-cargo-trouser', 'padded-overshirt-jacket', 'essential-heavyweight-t-shirt']),
            'is_bestseller' => $data['is_bestseller'] ?? false,
            'is_new' => $data['is_new'] ?? false,
            'is_limited' => $data['is_limited'] ?? false,
            'is_exclusive' => $data['is_exclusive'] ?? false,
            'fit' => $data['fit'],
            'material' => $data['material'],
            'style' => $data['style'],
            'care_instructions' => $data['care'] ?? 'Machine wash cold with similar colours. Dry flat away from direct heat.',
            'tags' => [$data['style']],
            'meta_title' => $data['name'] . ' | ' . $category->name . ' | Human In Motion',
            'meta_description' => str_replace(["\n", '  '], ' ', $data['description']),
            'published_at' => now()->subDays(random_int(1, 45)),
        ]);

        $sizes = match ($category->slug) {
            'trousers', 'jeans', 'shorts' => $this->bottoms,
            default => $this->oneSize,
        };

        if (in_array($category->slug, ['t-shirts', 'shirts', 'hoodies', 'sweatshirts', 'knitwear', 'jackets', 'coats', 'tracksuits', 'sets'])) {
            $sizes = $this->tops;
        }

        $position = 0;
        $product->variants()->delete();
        foreach ($data['colours'] as $colourSlug => $stockPerKey) {
            foreach ($sizes as $size) {
                $stock = is_int($stockPerKey)
                    ? max(0, intdiv($stockPerKey, max(1, count($sizes))) + random_int(-1, 3))
                    : $stockPerKey;
                if ($sizes === $this->oneSize && is_int($stockPerKey)) {
                    $stock = $stockPerKey;
                }
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => strtoupper('HIM-' . str_replace('-', '', $data['slug']) . '-' . $colourSlug . '-' . $size),
                    'size' => $size,
                    'colour' => strtoupper($colourSlug),
                    'price' => $data['price'],
                    'compare_price' => $data['compare_price'] ?? null,
                    'stock' => $stock,
                    'low_stock_threshold' => 4,
                    'is_active' => true,
                    'position' => $position++,
                ]);
            }
        }

        $product->images()->delete();
        $count = 3;
        foreach (range(1, $count) as $i) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => "/placeholder/{$data['slug']}-{$i}.svg?c=" . collect($data['colours'])->keys()->first(),
                'alt_text' => $data['name'] . (in_array($i, [2, 3]) ? ' — detail' : ''),
                'position' => $i,
            ]);
        }

        $this->assignCollections($product);
    }

    /**
     * Collections are merchandising entities (§30). Seed assignments here so the
     * homepage fixtures can power the demo experience.
     */
    private function assignCollections(Product $product): void
    {
        $map = [
            'core' => [
                'essential-heavyweight-t-shirt', 'long-sleeve-crew-tee', 'essential-heavyweight-hoodie',
                'essential-fleece-crew', 'essential-track-jacket', 'essential-track-pant',
                'ribbed-beanie', 'crew-socks-3-pack', 'slim-stretch-denim',
            ],
            'signature' => [
                'signature-oversized-hoodie', 'signature-oversized-t-shirt', 'boxfit-fleece-hoodie',
                'oversized-crew-sweat', 'relaxed-wide-denim', 'varsity-bomber-jacket',
                'technical-tapered-trouser', 'wool-blend-overcoat',
            ],
            'essentials' => [
                'essential-heavyweight-t-shirt', 'essential-heavyweight-hoodie', 'essential-fleece-crew',
                'relaxed-cargo-trouser', 'slim-stretch-denim', 'cargo-short', 'structured-cap',
                'ribbed-beanie', 'crew-socks-3-pack',
            ],
            'limited-edition' => [
                'box-fit-logo-tee', 'boxfit-fleece-hoodie', 'varsity-bomber-jacket',
            ],
            'seasonal' => [
                'checked-flannel-shirt', 'chunky-rib-knit', 'merino-crew-neck', 'padded-overshirt-jacket',
                'utility-field-coat', 'rain-shell-jacket',
            ],
            'new-season' => [
                'signature-oversized-hoodie', 'signature-oversized-t-shirt', 'oversized-crew-sweat',
                'padded-overshirt-jacket', 'technical-tapered-trouser', 'half-zip-technical-hoodie',
            ],
        ];

        foreach ($map as $collectionSlug => $slugs) {
            if (in_array($product->slug, $slugs)) {
                $product->collections()->syncWithoutDetaching(
                    Collection::query()->where('slug', $collectionSlug)->pluck('id')
                );
            }
        }
    }
}