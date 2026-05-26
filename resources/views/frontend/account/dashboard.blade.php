@extends('layouts.frontend')

@section('title', 'My Account - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <h1 class="mb-4">My dashboard</h1>
        @include('frontend.account._nav')
        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="mini-panel p-4"><div class="text-muted">Orders</div><div class="display-6 fw-bold">{{ $orders->count() }}</div></div></div>
            <div class="col-md-4"><div class="mini-panel p-4"><div class="text-muted">Wishlist</div><div class="display-6 fw-bold">{{ $wishlistCount }}</div></div></div>
            <div class="col-md-4"><div class="mini-panel p-4"><div class="text-muted">Addresses</div><div class="display-6 fw-bold">{{ $addressCount }}</div></div></div>
        </div>
        <div class="mini-panel p-4">
            <h2 class="h4 mb-3">Recent orders</h2>
            @include('frontend.account.orders-table', ['orders' => $orders])
        </div>
    </section>
@endsection
