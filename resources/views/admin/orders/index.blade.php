@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
            <h2 class="h5">Order management</h2>
            <form method="GET" class="d-flex gap-2">
                <input name="search" class="form-control" value="{{ request('search') }}" placeholder="Order/customer/email">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach
                </select>
                <button class="btn btn-outline-secondary"><i class="fa-solid fa-filter"></i></button>
            </form>
        </div>
        <div class="table-responsive"><table class="table"><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead><tbody>
            @forelse($orders as $order)
                <tr><td><strong>{{ $order->order_number }}</strong></td><td>{{ $order->customer_name }}<div class="small text-muted">{{ $order->customer_email }}</div></td><td>Rs. {{ number_format($order->grand_total, 2) }}</td><td>{{ ucfirst($order->payment_status) }}</td><td><span class="badge text-bg-light border">{{ ucfirst($order->status) }}</span></td><td>{{ $order->created_at->format('d M Y') }}</td><td><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.show', $order) }}">View</a></td></tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No orders.</td></tr>
            @endforelse
        </tbody></table></div>{{ $orders->links() }}
    </div>
@endsection
