@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Categories</h1>
            <p class="text-graphite mt-1">{{ $categories->count() }} categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary btn-sm">New category</a>
    </header>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Slug</th>
                    <th class="text-center">Products</th>
                    <th>Parent</th>
                    <th>Nav</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="font-semibold">{{ $category->name }}</td>
                        <td class="text-graphite text-xs">{{ $category->slug }}</td>
                        <td class="text-center text-graphite">{{ $category->products_count }}</td>
                        <td class="text-graphite text-xs">{{ $category->parent?->name ?: '—' }}</td>
                        <td class="text-center">{{ $category->show_in_nav ? 'Yes' : 'No' }}</td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'badge-brass' : 'badge-dark' }}">{{ $category->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('category.show', $category) }}" target="_blank" class="text-xs underline underline-offset-4 hover:text-brass">View</a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Delete {{ $category->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-10">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection