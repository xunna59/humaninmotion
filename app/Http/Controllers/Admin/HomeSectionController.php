<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\HomepageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeSectionController extends Controller
{
    public function index(): View
    {
        return view('admin.home.index', [
            'sections' => HomepageSection::query()->orderBy('sort_order')->get(),
            'title' => 'Homepage',
        ]);
    }

    public function create(): View
    {
        return view('admin.home.form', [
            'section' => new HomepageSection([
                'is_active' => true,
                'sort_order' => HomepageSection::query()->max('sort_order') + 10,
                'text_position' => 'left',
                'overlay_strength' => 40,
                'type' => HomepageSection::TYPE_HERO,
            ]),
            'title' => 'New homepage section',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        HomepageSection::create($data);
        AuditLog::record('admin.home_section.created', null, ['type' => $data['type'] ?? null, 'title' => $data['title'] ?? null]);

        return redirect()->route('admin.home.index')->with('status', 'Section created.');
    }

    public function edit(HomepageSection $section): View
    {
        return view('admin.home.form', [
            'section' => $section,
            'title' => 'Edit homepage section',
        ]);
    }

    public function update(Request $request, HomepageSection $section): RedirectResponse
    {
        $data = $this->validated($request);

        $section->update($data);
        AuditLog::record('admin.home_section.updated', $section, ['type' => $section->type]);

        return redirect()->route('admin.home.index')->with('status', 'Section saved.');
    }

    public function destroy(HomepageSection $section): RedirectResponse
    {
        $section->delete();
        AuditLog::record('admin.home_section.deleted', null, ['type' => $section->type]);

        return redirect()->route('admin.home.index')->with('status', 'Section removed.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        foreach ((array) $request->input('order', []) as $id => $sortOrder) {
            HomepageSection::query()->whereKey($id)->update(['sort_order' => (int) $sortOrder]);
        }

        AuditLog::record('admin.home_section.reordered');

        return back()->with('status', 'Section order saved.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'in:hero,image_banner,product_carousel,product_grid,collection_tiles,editorial,promotional_banner,category_grid,newsletter,custom'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_desktop' => ['nullable', 'string', 'max:500'],
            'image_mobile' => ['nullable', 'string', 'max:500'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'button_two_text' => ['nullable', 'string', 'max:120'],
            'button_two_url' => ['nullable', 'string', 'max:500'],
            'text_position' => ['nullable', 'in:left,center,right'],
            'overlay_strength' => ['nullable', 'integer', 'min:0', 'max:100'],
            'content' => ['nullable', 'json'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}