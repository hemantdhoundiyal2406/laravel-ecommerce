@extends('layouts.frontend')

@section('title', 'Order Placed - UrbanCart')

@section('content')
    <section class="container py-5">
        <div class="mini-panel p-5 text-center mx-auto" style="max-width: 760px">
            <i class="fa-solid fa-circle-check fa-4x text-success mb-3"></i>
            <h1 class="display-6 fw-bold">Order placed successfully</h1>
            <p class="lead text-muted">Your order number is <strong>{{ $order->order_number }}</strong>.</p>
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 mt-4">
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-brand">View Order</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>
        </div>
    </section>
@endsection
