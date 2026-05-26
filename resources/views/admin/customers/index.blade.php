@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between mb-3"><h2 class="h5">Customer management</h2><form method="GET" class="d-flex gap-2"><input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search customer"><button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button></form></div>
        <div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Status</th><th></th></tr></thead><tbody>
            @forelse($customers as $customer)
                <tr><td>{{ $customer->name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->phone }}</td><td>{{ $customer->orders()->count() }}</td><td><span class="badge {{ $customer->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($customer->status) }}</span></td><td><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.customers.show', $customer) }}">View</a></td></tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No customers.</td></tr>
            @endforelse
        </tbody></table></div>{{ $customers->links() }}
    </div>
@endsection
