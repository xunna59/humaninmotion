@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">Return {{ $returnRequest->number ?: '#' . $returnRequest->id }}</h1>
        <p class="text-graphite mt-1">
            <a href="{{ route('admin.returns.index') }}" class="underline underline-offset-4">Returns</a>
            · <a href="{{ route('admin.orders.show', $returnRequest->order) }}" class="underline underline-offset-4">Order {{ $returnRequest->order->order_number }}</a>
        </p>
    </header>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-5">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Items</h2>
                <table class="table-base w-full">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th>Reason</th>
                            <th>Condition</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returnRequest->items as $item)
                            <tr>
                                <td class="text-sm">{{ $item->orderItem?->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-xs text-graphite">{{ $item->reason ?: '—' }}</td>
                                <td class="text-xs text-graphite">{{ $item->condition ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-graphite">No items recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card p-5">
                <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-3">Comments</h2>
                <p class="text-sm text-graphite">{{ $returnRequest->comments ?: 'No comments.' }}</p>
            </div>
        </div>

        <div class="card p-5">
            <h2 class="font-semibold uppercase tracking-[--tracking-label] text-sm mb-4">Manage return</h2>
            <form method="POST" action="{{ route('admin.returns.update', $returnRequest) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="label" for="status">Status</label>
                    <select class="select" id="status" name="status">
                        @foreach (['requested','approved','rejected','received','inspected','refunded','completed'] as $status)
                            <option value="{{ $status }}" @selected($returnRequest->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="resolution">Resolution</label>
                    <textarea class="input" id="resolution" name="resolution" rows="4">{{ old('resolution', $returnRequest->resolution) }}</textarea>
                </div>
                <button class="btn-primary btn-sm w-full">Save</button>
            </form>
        </div>
    </div>
@endsection