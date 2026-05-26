@extends('layouts.admin')

@section('title', $coupon->exists ? 'Edit Coupon' : 'Add Coupon')

@section('content')
    <div class="admin-card p-4">
        <form action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
            @csrf @if($coupon->exists) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Code</label><input name="code" class="form-control text-uppercase" value="{{ old('code', $coupon->code) }}" required></div>
                <div class="col-md-4"><label class="form-label">Discount type</label><select name="discount_type" class="form-select"><option value="percentage" @selected(old('discount_type', $coupon->discount_type) === 'percentage')>Percentage</option><option value="fixed" @selected(old('discount_type', $coupon->discount_type) === 'fixed')>Fixed</option></select></div>
                <div class="col-md-4"><label class="form-label">Discount value</label><input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" required></div>
                <div class="col-md-4"><label class="form-label">Minimum order</label><input type="number" step="0.01" name="minimum_order_amount" class="form-control" value="{{ old('minimum_order_amount', $coupon->minimum_order_amount ?? 0) }}" required></div>
                <div class="col-md-4"><label class="form-label">Usage limit</label><input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}"></div>
                <div class="col-md-4"><label class="form-label">Expiry date</label><input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"></div>
                <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))> <span class="form-check-label">Active</span></label></div>
                <div class="col-12 d-flex gap-2"><button class="btn btn-success">Save Coupon</button><a class="btn btn-outline-secondary" href="{{ route('admin.coupons.index') }}">Cancel</a></div>
            </div>
        </form>
    </div>
@endsection
