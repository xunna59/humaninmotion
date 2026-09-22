@extends('admin.layouts.app')

@section('content')
    @include('admin.partials.flash')

    <header class="mb-6">
        <h1 class="display-campaign text-4xl">Customers</h1>
        <p class="text-graphite mt-1">{{ $customers->total() }} customers</p>
    </header>

    <form method="GET" class="card p-4 mb-6 grid sm:grid-cols-3 gap-3">
        <div class="sm:col-span-1">
            <label class="label" for="q">Search</label>
            <input class="input" type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Name or email">
        </div>
        <div>
            <label class="label" for="role">Role</label>
            <select class="select" id="role" name="role">
                <option value="">All</option>
                @foreach (['customer' => 'Customer', 'admin' => 'Admin', 'super_admin' => 'Super admin', 'content_manager' => 'Content manager', 'support' => 'Support'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-3">
            <button class="btn-bone btn-sm">Filter</button>
            @if (request()->hasAny('q', 'role'))
                <a href="{{ route('admin.customers.index') }}" class="text-sm text-graphite pb-3 hover:text-ink">Clear</a>
            @endif
        </div>
    </form>

    <div class="card overflow-x-auto">
        <table class="table-base w-full">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Orders</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th class="text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td class="font-semibold">{{ $customer->name }}</td>
                        <td class="text-graphite text-xs">{{ $customer->email }}</td>
                        <td class="text-xs capitalize">{{ str_replace('_', ' ', $customer->role) }}</td>
                        <td class="text-center text-graphite">{{ $customer->orders_count }}</td>
                        <td class="text-xs text-graphite">{{ $customer->created_at->format('j M Y') }}</td>
                        <td>
                            <span class="badge {{ $customer->is_active ? 'badge-brass' : 'badge-sale' }}">{{ $customer->is_active ? 'Active' : 'Disabled' }}</span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-xs underline underline-offset-4 hover:text-brass">Profile</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-graphite py-10">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $customers->links() }}</div>
@endsection