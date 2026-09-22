@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Collections</h1>
            <p class="text-graphite mt-1">{{ $collections->count() }} collections</p>
        </div>
        <a href="{{ route('admin.collections.create') }}" class="btn-primary btn-sm">New collection</a>
    </header>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Collection</th>
                    <th>Slug</th>
                    <th class="text-center">Products</th>
                    <th>Dates</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($collections as $collection)
                    <tr>
                        <td class="font-semibold">
                            {{ $collection->name }}
                            @if ($collection->is_featured)
                                <span class="badge badge-brass ml-2">Featured</span>
                            @endif
                        </td>
                        <td class="text-graphite text-xs">{{ $collection->slug }}</td>
                        <td class="text-center text-graphite">{{ $collection->products_count }}</td>
                        <td class="text-xs text-graphite">
                            @if ($collection->starts_at || $collection->ends_at)
                                @if ($collection->starts_at) {{ $collection->starts_at->toDateString() }} @endif
                                →
                                @if ($collection->ends_at) {{ $collection->ends_at->toDateString() }} @endif
                            @else
                                Always
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $collection->is_active ? 'badge-brass' : 'badge-dark' }}">{{ $collection->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('collection.show', $collection) }}" target="_blank" class="text-xs underline underline-offset-4 hover:text-brass">View</a>
                                <a href="{{ route('admin.collections.edit', $collection) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.collections.destroy', $collection) }}"
                                      onsubmit="return confirm('Delete {{ $collection->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-graphite py-10">No collections yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection