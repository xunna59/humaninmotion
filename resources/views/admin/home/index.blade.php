@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Homepage</h1>
            <p class="text-graphite mt-1">{{ $sections->count() }} sections — display order top to bottom</p>
        </div>
        <a href="{{ route('admin.home.create') }}" class="btn-primary btn-sm">New section</a>
    </header>

    <form method="POST" action="{{ route('admin.home.reorder') }}">
        @csrf
        @php $active = $sections->where('is_active', true)->count(); @endphp

        <div class="space-y-3">
            @foreach ($sections as $section)
                <div class="card p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <span class="text-graphite font-mono w-6 text-center">{{ $loop->iteration }}</span>
                        <div class="min-w-0">
                            <p class="font-semibold truncate">{{ $section->title ?: ucwords(str_replace('_', ' ', $section->type)) }}</p>
                            <p class="text-graphite text-xs">{{ str_replace('_', ' ', $section->type) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <input type="number" class="input w-20 py-2 text-center" name="order[{{ $section->id }}]" value="{{ $section->sort_order }}" title="Sort order">
                        <span class="badge {{ $section->is_active ? 'badge-brass' : 'badge-dark' }}">{{ $section->is_active ? 'Live' : 'Hidden' }}</span>
                        <a href="{{ route('admin.home.edit', $section) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                        <form method="POST" action="{{ route('admin.home.destroy', $section) }}" class="inline"
                              onsubmit="return confirm('Delete this homepage section?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($active < 3)
            <p class="text-xs text-warn mt-4">Note: fewer than 3 sections are active — the homepage will look sparse.</p>
        @endif

        <button class="btn-primary btn-sm mt-6">Save order</button>
    </form>
@endsection