<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::query()
                ->whereNotIn('slug', Page::templateSlugs())
                ->orderBy('title')
                ->get(),
            'title' => 'Pages',
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.form', [
            'page' => new Page(['is_active' => true]),
            'title' => 'New page',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        return $this->save($request, new Page, $data);
    }

    public function edit(Page $page): View|RedirectResponse
    {
        if ($redirect = $this->templateRedirect($page)) {
            return $redirect;
        }

        return view('admin.pages.form', [
            'page' => $page,
            'title' => 'Edit: '.$page->title,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        if ($redirect = $this->templateRedirect($page)) {
            return $redirect;
        }

        $data = $this->validated($request, $page);

        return $this->save($request, $page, $data);
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($redirect = $this->templateRedirect($page)) {
            return $redirect;
        }

        AuditLog::record('admin.page.deleted', $page, ['title' => $page->title]);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Page deleted.');
    }

    private function templateRedirect(Page $page): ?RedirectResponse
    {
        return in_array($page->slug, Page::templateSlugs(), true)
            ? redirect()->route('admin.pages.index')->with('status', 'This page uses a built-in template and cannot be edited.')
            : null;
    }

    private function save(Request $request, Page $page, array $data): RedirectResponse
    {
        $data['is_active'] = $request->boolean('is_active');
        $slug = $data['slug'] ?: Str::slug($data['title']);

        if (in_array($slug, Page::templateSlugs(), true)) {
            return back()->withErrors(['slug' => 'That slug is reserved for a built-in template page.'])->withInput();
        }

        $data['slug'] = $slug;
        $page->fill($data)->save();
        AuditLog::record($page->wasRecentlyCreated ? 'admin.page.created' : 'admin.page.updated', $page, ['title' => $page->title]);

        return redirect()->route('admin.pages.index')->with('status', $page->wasRecentlyCreated ? 'Page created.' : 'Page saved.');
    }

    private function validated(Request $request, ?Page $page = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:pages,slug'.($page ? ','.$page->id : '')],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
