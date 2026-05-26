@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between mb-3"><h2 class="h5">Coupon management</h2><a class="btn btn-success" href="{{ route('admin.coupons.create') }}"><i class="fa-solid fa-plus me-1"></i>Add Coupon</a></div>
        <div class="table-responsive"><table class="table"><thead><tr><th>Code</th><th>Discount</th><th>Min Order</th><th>Usage</th><th>Expiry</th><th>Status</th><th></th></tr></thead><tbody>
            @forelse($coupons as $coupon)
                <tr><td><strong>{{ $coupon->code }}</strong></td><td>{{ $coupon->discount_type === 'percentage' ? $coupon->discount_value.'%' : 'Rs. '.number_format($coupon->discount_value, 2) }}</td><td>Rs. {{ number_format($coupon->minimum_order_amount, 2) }}</td><td>{{ $coupon->used_count }}/{{ $coupon->usage_limit ?: '∞' }}</td><td>{{ $coupon->expires_at?->format('d M Y') ?: '-' }}</td><td><span class="badge {{ $coupon->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</span></td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.coupons.edit', $coupon) }}"><i class="fa-solid fa-pen"></i></a> <form class="d-inline" method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form></td></tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No coupons.</td></tr>
            @endforelse
        </tbody></table></div>{{ $coupons->links() }}
    </div>
@endsection
