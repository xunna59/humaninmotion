<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\Catalogue\ProductQuery;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $category = Category::query()
            ->with('parent')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $filters = array_merge($this->parse($request), ['category' => $slug]);
        $sort = $request->input('sort', 'featured');
        $query = app(ProductQuery::class)->withFilters($filters);
        $products = $query->paginate(ShopController::PER_PAGE, $sort);
        $facetBase = $query->build();

        return view('shop.index', [
            'heading' => $category->name,
            'category' => $category,
            'description' => $category->description,
            'products' => $products,
            'sort' => $sort,
            'filters' => $filters,
            'facets' => [
                'colours' => ProductQuery::availableColours($facetBase),
                'sizes' => $this->sizes($facetBase),
            ],
            'crumbs' => $category->parent ? [['label' => 'Shop', 'url' => route('shop')], ['label' => $category->parent->name, 'url' => route('category.show', $category->parent->slug)]] : [['label' => 'Shop', 'url' => route('shop')]],
            'title' => $category->name . ' | Human In Motion',
        ]);
    }

    protected function parse(Request $request): array
    {
        return [
            'sizes' => $request->input('sizes', []),
            'colours' => $request->input('colours', []),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'in_stock' => (bool) $request->boolean('in_stock'),
            'fit' => $request->input('fit'),
            'style' => $request->input('style'),
        ];
    }

    protected function sizes($query): array
    {
        return array_values(array_unique(
            $query->clone()
                ->active()
                ->leftJoin('product_variants', fn ($j) => $j->on('product_variants.product_id', '=', 'products.id')->where('product_variants.is_active', true))
                ->whereNotNull('product_variants.size')
                ->pluck('product_variants.size')
                ->all()
        ));
    }
}