@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Reviews</h1>
            <p class="text-graphite mt-1">{{ $reviews->total() }} reviews</p>
        </div>
        <form method="GET">
            <select class="select" name="status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </header>

    <div class="space-y-4">
        @forelse ($reviews as $review)
            <div class="card p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold">{{ $review->title ?: 'Untitled review' }}</p>
                        <p class="text-graphite text-xs mt-0.5">
                            {{ $review->rating }}/5 · {{ $review->user?->name ?: 'Deleted user' }} on
                            <a href="{{ route('admin.products.edit', $review->product) }}" class="underline underline-offset-4 hover:text-brass">{{ $review->product?->name }}</a>
                        </p>
                        @if ($review->is_verified)
                            <span class="badge badge-brass mt-1">Verified purchase</span>
                        @endif
                    </div>
                    <span class="badge {{ match ($review->status) {
                            'approved' => 'badge-brass',
                            'rejected' => 'badge-sale',
                            default => 'badge-dark',
                        } }}">{{ ucfirst($review->status) }}</span>
                </div>
                @if ($review->comment)
                    <p class="text-sm text-ink/80 mt-3">{{ $review->comment }}</p>
                @endif
                <div class="flex gap-2 mt-4">
                    @if ($review->status !== 'approved')
                        <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button class="btn-primary btn-sm">Approve</button>
                        </form>
                    @endif
                    @if ($review->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn-bone btn-sm">Reject</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                          onsubmit="return confirm('Delete this review?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn-outline btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="card p-10 text-center text-graphite">No reviews found.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $reviews->links() }}</div>
@endsection