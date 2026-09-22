<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Services\Catalogue\ProductQuery;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get()
            ->map(function (Collection $collection) {
                $collection->setRelation('products', \App\Models\Product::query()
                    ->active()
                    ->whereHas('collections', fn ($q) => $q->where('collections.id', $collection->id))
                    ->take(4)
                    ->get());

                return $collection;
            });

        return view('shop.collections', [
            'collections' => $collections,
            'title' => 'Collections | Human In Motion',
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $collection = Collection::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $filters = array_merge($this->parse($request), ['collection' => $slug]);
        $sort = $request->input('sort', 'featured');
        $query = app(ProductQuery::class)->withFilters($filters);
        $products = $query->paginate(ShopController::PER_PAGE, $sort);
        $facetBase = $query->build();

        return view('shop.collection', [
            'collection' => $collection,
            'products' => $products,
            'sort' => $sort,
            'filters' => $filters,
            'facets' => [
                'colours' => ProductQuery::availableColours($facetBase),
            ],
            'title' => $collection->name . ' | Human In Motion',
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
}