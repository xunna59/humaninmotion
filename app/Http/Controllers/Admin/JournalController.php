<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\JournalArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View
    {
        return view('admin.journal.index', [
            'articles' => JournalArticle::query()
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get(),
            'title' => 'Journal',
        ]);
    }

    public function create(): View
    {
        return view('admin.journal.form', [
            'article' => new JournalArticle(['status' => JournalArticle::STATUS_DRAFT, 'gallery' => []]),
            'title' => 'New article',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['status'] = $request->input('status', JournalArticle::STATUS_DRAFT);
        $data['published_at'] = $this->resolvePublishedAt($data['published_at'] ?? null, $data['status']);
        $data['gallery'] = [];

        $article = JournalArticle::create($data);

        if ($request->hasFile('cover_image')) {
            $article->update(['cover_image' => $this->storeImage($request->file('cover_image'))]);
        }

        $article->update(['gallery' => $this->handleGallery($request, $article)]);
        AuditLog::record('admin.journal.created', $article, ['title' => $article->title]);

        return redirect()->route('admin.journal.index')->with('status', 'Article created.');
    }

    public function edit(JournalArticle $article): View
    {
        return view('admin.journal.form', [
            'article' => $article,
            'title' => 'Edit: '.$article->title,
        ]);
    }

    public function update(Request $request, JournalArticle $article): RedirectResponse
    {
        $data = $this->validated($request, $article);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['status'] = $request->input('status', JournalArticle::STATUS_DRAFT);
        $data['published_at'] = $this->resolvePublishedAt($data['published_at'] ?? null, $data['status']);
        $data['gallery'] = $this->handleGallery($request, $article);

        $article->update($data);

        if ($request->hasFile('cover_image')) {
            $this->deleteManagedFile($article->cover_image);
            $article->update(['cover_image' => $this->storeImage($request->file('cover_image'))]);
        } elseif ($request->boolean('remove_cover') && $article->cover_image) {
            $this->deleteManagedFile($article->cover_image);
            $article->update(['cover_image' => null]);
        }

        AuditLog::record('admin.journal.updated', $article, ['title' => $article->title]);

        return redirect()->route('admin.journal.index')->with('status', 'Article saved.');
    }

    public function destroy(JournalArticle $article): RedirectResponse
    {
        AuditLog::record('admin.journal.deleted', $article, ['title' => $article->title]);

        $this->deleteManagedFile($article->cover_image);
        foreach ($article->gallery ?? [] as $path) {
            $this->deleteManagedFile($path);
        }

        $article->delete();

        return redirect()->route('admin.journal.index')->with('status', 'Article deleted.');
    }

    private function validated(Request $request, ?JournalArticle $article = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:journal_articles,slug'.($article ? ','.$article->id : '')],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'cover_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
            'remove_cover' => ['nullable', 'boolean'],
            'gallery_keep' => ['nullable', 'array'],
            'gallery_keep.*' => ['nullable', 'string', 'max:500'],
            'gallery_new' => ['nullable', 'array'],
            'gallery_new.*' => ['file', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
        ]);
    }

    private function handleGallery(Request $request, JournalArticle $article): array
    {
        $keep = collect($request->input('gallery_keep', []))
            ->filter()
            ->values()
            ->all();

        $new = [];

        foreach ((array) $request->file('gallery_new', []) as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $new[] = $this->storeImage($file);
            }
        }

        $gallery = array_values(array_unique(array_merge($keep, $new)));

        foreach (array_diff($article->gallery ?? [], $gallery) as $removed) {
            $this->deleteManagedFile($removed);
        }

        return $gallery;
    }

    private function storeImage(UploadedFile $file): string
    {
        return $file->store('journal-covers', 'public');
    }

    private function deleteManagedFile(?string $path): void
    {
        if ($path && str_starts_with($path, 'journal-covers/')) {
            Storage::disk('public')->delete($path);
        }
    }

    private function resolvePublishedAt(?string $value, string $status): ?string
    {
        if ($value) {
            return $value;
        }

        return $status === JournalArticle::STATUS_PUBLISHED ? now()->toDateTimeString() : null;
    }
}
