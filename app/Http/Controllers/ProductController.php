<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Catalogue\ProductQuery;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::query()
            ->with([
                'category',
                'collections',
                'variants' => fn ($q) => $q->orderBy('position'),
                'images',
                'approvedReviews.user',
            ])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $colour = collect($product->availableColours())->first() ?: 'BLACK';
        $baseQuery = app(ProductQuery::class)->withFilters(['category' => $product->category?->slug]);

        $related = $baseQuery->get('featured')
            ->where('id', '!=', $product->id)
            ->take(4);

        $completeTheLook = Product::query()
            ->active()
            ->where('id', '!=', $product->id)
            ->whereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0))
            ->inRandomOrder()
            ->take(4)
            ->get();

        DB::table('products')->where('id', $product->id)->increment('views');

        return view('shop.product', [
            'product' => $product,
            'colour' => strtoupper($colour),
            'related' => $related,
            'completeTheLook' => $completeTheLook,
            'reviews' => $product->approvedReviews,
            'title' => $product->meta_title ?: $product->getFullNameAttribute(),
        ]);
    }
}