<?php

namespace App\Http\Controllers;

use App\Models\JournalArticle;
use App\Models\Page;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View
    {
        return view('shop.journal', [
            'articles' => JournalArticle::query()
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->paginate(9),
            'title' => 'Journal | Human In Motion',
        ]);
    }

    public function show(string $slug): View
    {
        $article = JournalArticle::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('shop.article', [
            'article' => $article,
            'related' => JournalArticle::query()
                ->where('status', 'published')
                ->where('id', '!=', $article->id)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
            'title' => $article->meta_title ?: $article->title . ' | Human In Motion',
        ]);
    }
}