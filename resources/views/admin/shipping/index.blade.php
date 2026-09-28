@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
        <div>
            <h1 class="display-campaign text-4xl">Shipping</h1>
            <p class="text-graphite mt-1">{{ $methods->count() }} shipping methods · shown at checkout in this order</p>
        </div>
        <a href="{{ route('admin.shipping.create') }}" class="btn-primary btn-sm">New method</a>
    </header>

    @if ($errors->any())
        <div class="mb-4 p-4 border border-sale bg-sale/5 text-sale text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Method</th>
                    <th>Code</th>
                    <th class="text-center">Price</th>
                    <th>Free over</th>
                    <th>Estimate</th>
                    <th>Zones</th>
                    <th class="text-center">Order</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($methods as $method)
                    <tr>
                        <td class="font-semibold">{{ $method->name }}</td>
                        <td class="text-graphite text-xs">{{ $method->code }}</td>
                        <td class="text-center text-graphite">£{{ number_format((float) $method->price, 2) }}</td>
                        <td class="text-graphite text-xs">{{ $method->free_above !== null ? '£' . number_format((float) $method->free_above, 2) : '—' }}</td>
                        <td class="text-graphite text-xs">{{ $method->estimate ?: '—' }}</td>
                        <td class="text-graphite text-xs">{{ count($method->zones ?? []) }} {{ Str::plural('country', count($method->zones ?? [])) }}</td>
                        <td class="text-center text-graphite">{{ $method->sort_order }}</td>
                        <td>
                            <span class="badge {{ $method->is_active ? 'badge-brass' : 'badge-dark' }}">{{ $method->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('admin.shipping.edit', $method) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.shipping.destroy', $method) }}"
                                      onsubmit="return confirm('Delete {{ $method->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-graphite py-10">No shipping methods yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection