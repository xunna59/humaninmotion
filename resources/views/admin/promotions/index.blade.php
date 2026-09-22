@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Promotions</h1>
            <p class="text-graphite mt-1">{{ $promotions->count() }} promotions</p>
        </div>
        <a href="{{ route('admin.promotions.create') }}" class="btn-primary btn-sm">New promotion</a>
    </header>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Applies to</th>
                    <th>Badge</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($promotions as $promotion)
                    <tr>
                        <td class="font-semibold">{{ $promotion->name }}</td>
                        <td class="text-graphite text-xs">{{ ucfirst($promotion->type) }}</td>
                        <td>{{ $promotion->type === 'percentage' ? $promotion->value . '%' : '£' . number_format((float) $promotion->value, 2) }}</td>
                        <td class="text-graphite text-xs">{{ ucfirst($promotion->applies_to) }}</td>
                        <td>
                            @if ($promotion->badge)
                                <span class="badge badge-sale">{{ $promotion->badge }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $promotion->isRunning() ? 'badge-brass' : 'badge-dark' }}">{{ $promotion->isRunning() ? 'Running' : 'Inactive' }}</span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}"
                                      onsubmit="return confirm('Delete {{ $promotion->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-10">No promotions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection