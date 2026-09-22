@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="display-campaign text-4xl">Coupons</h1>
            <p class="text-graphite mt-1">{{ $coupons->count() }} coupons</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn-primary btn-sm">New coupon</a>
    </header>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th class="text-center">Uses</th>
                    <th>Valid</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    @php
                        $expired = $coupon->ends_at && $coupon->ends_at->isPast();
                        $opened = $coupon->starts_at && $coupon->starts_at->isFuture();
                    @endphp
                    <tr>
                        <td class="font-semibold tracking-wider">{{ $coupon->code }}</td>
                        <td class="text-graphite text-xs">{{ ucwords(str_replace('_', ' ', $coupon->type)) }}</td>
                        <td>
                            {{ $coupon->type === 'percentage' ? $coupon->value . '%' : ($coupon->type === 'fixed' ? '£' . number_format((float) $coupon->value, 2) : 'Free shipping') }}
                        </td>
                        <td class="text-center text-graphite">
                            {{ $coupon->usages_count }}
                            @if ($coupon->usage_limit)
                                / {{ $coupon->usage_limit }}
                            @endif
                        </td>
                        <td class="text-xs text-graphite">
                            @if ($expired)
                                <span class="text-sale">Expired {{ $coupon->ends_at->toDateString() }}</span>
                            @elseif ($opened)
                                Starts {{ $coupon->starts_at->toDateString() }}
                            @else
                                <span class="text-ok">Valid now</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $coupon->is_active && ! $expired ? 'badge-brass' : 'badge-dark' }}">{{ $coupon->is_active ? 'Active' : 'Disabled' }}</span>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex gap-2 items-center">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-xs underline underline-offset-4 hover:text-brass">Edit</a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}"
                                      onsubmit="return confirm('Delete {{ $coupon->code }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs underline underline-offset-4 text-sale hover:text-sale/70">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-10">No coupons yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection