<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('shop.page', [
            'page' => $page,
            'title' => $page->meta_title ?: $page->title . ' | Human In Motion',
        ]);
    }
}