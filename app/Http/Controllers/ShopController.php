<?php

namespace App\Http\Controllers;

use App\Services\Catalogue\ProductQuery;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public const PER_PAGE = 24;

    public function index(Request $request)
    {
        return $this->render($request, 'SHOP ALL', null);
    }

    public function newIn(Request $request)
    {
        $filters = array_merge($this->parse($request), ['new' => true]);

        return $this->render($request, 'NEW IN', $filters);
    }

    public function bestSellers(Request $request)
    {
        $filters = array_merge($this->parse($request), ['bestseller' => true]);

        return $this->render($request, 'BEST SELLERS', $filters);
    }

    public function sale(Request $request)
    {
        $filters = array_merge($this->parse($request), ['sale' => true]);

        return $this->render($request, 'SALE', $filters);
    }

    protected function render(Request $request, string $heading, ?array $filters): \Illuminate\View\View
    {
        $filters = $filters ?? $this->parse($request);
        $sort = $request->input('sort', 'featured');
        $query = app(ProductQuery::class)->withFilters($filters);
        $products = $query->paginate(self::PER_PAGE, $sort);
        $facetBase = $query->build();

        return view('shop.index', [
            'heading' => $heading,
            'products' => $products,
            'sort' => $sort,
            'filters' => $filters,
            'facets' => [
                'colours' => ProductQuery::availableColours($facetBase),
            ],
            'title' => $heading . ' | Human In Motion',
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
            'material' => $request->input('material'),
            'style' => $request->input('style'),
        ];
    }
}