<?php

namespace App\Http\Controllers;

use App\Services\Catalogue\ProductQuery;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Request $request)
    {
        $term = trim($request->string('q')->toString());

        $products = collect();
        $suggestions = [];

        if ($term !== '') {
            $term = preg_quote($term, '~');
            $products = app(ProductQuery::class)
                ->withFilters(['term' => $term])
                ->paginate(24, 'featured');

            $suggestions = [
                'categories' => \App\Models\Category::query()
                    ->where('is_active', true)
                    ->where(function ($q) use ($term) {
                        $q->where('name', 'like', "%{$term}%");
                    })
                    ->withCount('products')
                    ->get(),
                'collections' => \App\Models\Collection::query()
                    ->where('is_active', true)
                    ->where('name', 'like', "%{$term}%")
                    ->get(),
            ];
        }

        $popular = ['Hoodies', 'Cargo', 'T-Shirt', 'Trousers'];

        return view('shop.search', [
            'term' => $request->input('q', ''),
            'products' => $products,
            'suggestions' => $suggestions,
            'popular' => $popular,
            'title' => $term ? 'Search: ' . $request->input('q') . ' | Human In Motion' : 'Search | Human In Motion',
        ]);
    }
}