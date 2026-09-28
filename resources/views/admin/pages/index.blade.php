@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
        <div>
            <h1 class="display-campaign text-4xl">Pages</h1>
            <p class="text-graphite mt-1">{{ $pages->count() }} pages</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="btn-primary btn-sm">New page</a>
    </header>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Slug</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $page)
                    <tr>
                        <td class="font-semibold">{{ $page->title }}</td>
                        <td class="text-graphite text-xs">{{ $page->slug }}</td>
                        <td class="text-graphite text-xs">/pages/{{ $page->slug }}</td>
                        <td>
                            <span class="badge {{ $page->is_active ? 'badge-brass' : 'badge-dark' }}">{{ $page->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-xs underline underline-offset-4 hover:text-brass">View</a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
                                      onsubmit="return confirm('Delete {{ $page->title }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-graphite py-10">No pages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection