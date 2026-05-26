@extends('layouts.admin')

@section('title', 'Order '.$order->order_number)

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="admin-card p-4 mb-4">
                <div class="d-flex justify-content-between gap-3 mb-3">
                    <h2 class="h5">Order items</h2>
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.orders.invoice', $order) }}" target="_blank"><i class="fa-solid fa-file-invoice me-1"></i>Invoice</a>
                </div>
                <div class="table-responsive"><table class="table"><thead><tr><th>Product</th><th>SKU</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead><tbody>
                    @foreach($order->items as $item)
                        <tr><td>{{ $item->product_name }}<div class="small text-muted">{{ $item->variant_label }}</div></td><td>{{ $item->sku }}</td><td>{{ $item->quantity }}</td><td>Rs. {{ number_format($item->price, 2) }}</td><td>Rs. {{ number_format($item->total, 2) }}</td></tr>
                    @endforeach
                </tbody></table></div>
            </div>
            <div class="admin-card p-4">
                <h2 class="h5">Customer & addresses</h2>
                <div class="row g-3">
                    <div class="col-md-4"><strong>{{ $order->customer_name }}</strong><br>{{ $order->customer_email }}<br>{{ $order->customer_phone }}</div>
                    <div class="col-md-4"><strong>Billing</strong><br>{{ $order->billing_address['address_line1'] ?? '' }}<br>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }}</div>
                    <div class="col-md-4"><strong>Shipping</strong><br>{{ $order->shipping_address['address_line1'] ?? '' }}<br>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="admin-card p-4 mb-4">
                <h2 class="h5">Update order</h2>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <label class="form-label">Order status</label><select name="status" class="form-select mb-3">@foreach(['pending','processing','shipped','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
                    <label class="form-label">Payment status</label><select name="payment_status" class="form-select mb-3">@foreach(['pending','paid','failed','refunded','awaiting_payment'] as $status)<option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst(str_replace('_',' ', $status)) }}</option>@endforeach</select>
                    <label class="form-label">Tracking number</label><input name="tracking_number" class="form-control mb-3" value="{{ $order->tracking_number }}">
                    <label class="form-label">Admin note</label><textarea name="admin_note" class="form-control mb-3" rows="4">{{ $order->admin_note }}</textarea>
                    <button class="btn btn-success w-100">Update Order</button>
                </form>
            </div>
            <div class="admin-card p-4">
                <h2 class="h5">Summary</h2>
                <div class="d-grid gap-2">
                    <div class="d-flex justify-content-between"><span>Subtotal</span><strong>Rs. {{ number_format($order->subtotal, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Discount</span><strong>- Rs. {{ number_format($order->discount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Shipping</span><strong>Rs. {{ number_format($order->shipping_charge, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Tax</span><strong>Rs. {{ number_format($order->tax, 2) }}</strong></div>
                    <hr><div class="d-flex justify-content-between fs-5"><span>Total</span><strong>Rs. {{ number_format($order->grand_total, 2) }}</strong></div>
                </div>
            </div>
        </div>
    </div>
@endsection
