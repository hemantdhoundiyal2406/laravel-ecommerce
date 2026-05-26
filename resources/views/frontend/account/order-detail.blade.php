@extends('layouts.frontend')

@section('title', 'Order '.$order->order_number.' - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
            <div>
                <span class="text-uppercase small text-muted fw-bold">Order details</span>
                <h1 class="mb-0">{{ $order->order_number }}</h1>
            </div>
            <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary align-self-start">Back to Orders</a>
        </div>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="mini-panel p-4">
                    <h2 class="h4 mb-3">Items</h2>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
                            <tbody>
                            @foreach ($order->items as $item)
                                <tr><td>{{ $item->product_name }}<div class="small text-muted">{{ $item->variant_label }}</div></td><td>{{ $item->quantity }}</td><td>Rs. {{ number_format($item->price, 2) }}</td><td>Rs. {{ number_format($item->total, 2) }}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mini-panel p-4 mb-4">
                    <h2 class="h4">Tracking</h2>
                    <div class="d-grid gap-2">
                        <div>Status: <span class="badge text-bg-light border">{{ ucfirst($order->status) }}</span></div>
                        <div>Payment: <span class="badge text-bg-light border">{{ ucfirst($order->payment_status) }}</span></div>
                        <div>Tracking: {{ $order->tracking_number ?: 'Not assigned yet' }}</div>
                    </div>
                </div>
                <div class="mini-panel p-4">
                    <h2 class="h4">Totals</h2>
                    <div class="d-grid gap-2">
                        <div class="d-flex justify-content-between"><span>Subtotal</span><strong>Rs. {{ number_format($order->subtotal, 2) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Discount</span><strong>- Rs. {{ number_format($order->discount, 2) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Shipping</span><strong>Rs. {{ number_format($order->shipping_charge, 2) }}</strong></div>
                        <div class="d-flex justify-content-between"><span>Tax</span><strong>Rs. {{ number_format($order->tax, 2) }}</strong></div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5"><span>Total</span><strong>Rs. {{ number_format($order->grand_total, 2) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
