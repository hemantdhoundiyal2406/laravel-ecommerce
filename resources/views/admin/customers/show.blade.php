@extends('layouts.admin')

@section('title', 'Customer '.$customer->name)

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="admin-card p-4">
                <h2 class="h5">{{ $customer->name }}</h2>
                <p class="mb-1">{{ $customer->email }}</p>
                <p class="text-muted">{{ $customer->phone }}</p>
                <form action="{{ route('admin.customers.status', $customer) }}" method="POST">
                    @csrf @method('PATCH')
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select mb-3"><option value="active" @selected($customer->status === 'active')>Active</option><option value="inactive" @selected($customer->status === 'inactive')>Inactive</option></select>
                    <button class="btn btn-success">Update Status</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="admin-card p-4">
                <h2 class="h5">Order history</h2>
                <div class="table-responsive"><table class="table"><thead><tr><th>Order</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody>@forelse($customer->orders as $order)<tr><td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td><td>Rs. {{ number_format($order->grand_total, 2) }}</td><td>{{ ucfirst($order->status) }}</td><td>{{ $order->created_at->format('d M Y') }}</td></tr>@empty<tr><td colspan="4" class="text-muted text-center">No orders.</td></tr>@endforelse</tbody></table></div>
            </div>
        </div>
    </div>
@endsection
