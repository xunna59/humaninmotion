@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">Returns</h1>
        <p class="text-graphite mt-1">{{ $returns->count() }} return requests</p>
    </header>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Return</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Requested</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($returns as $returnRequest)
                    <tr>
                        <td class="font-semibold">{{ $returnRequest->number ?: '#' . $returnRequest->id }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $returnRequest->order) }}" class="text-xs underline underline-offset-4 hover:text-brass">
                                {{ $returnRequest->order->order_number }}
                            </a>
                        </td>
                        <td class="text-graphite text-xs">{{ $returnRequest->user?->email ?: 'Guest' }}</td>
                        <td class="text-graphite text-xs max-w-[200px] truncate">{{ $returnRequest->reason ?: '—' }}</td>
                        <td>
                            <span class="badge {{ match ($returnRequest->status) {
                                    'requested' => 'badge-dark',
                                    'refunded', 'completed' => 'badge-brass',
                                    'rejected' => 'badge-sale',
                                    default => 'badge-bone',
                                } }}">{{ ucfirst($returnRequest->status) }}</span>
                        </td>
                        <td class="text-xs text-graphite">{{ $returnRequest->created_at->format('j M Y') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.returns.show', $returnRequest) }}" class="text-xs underline underline-offset-4 hover:text-brass">Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-10">No returns yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection