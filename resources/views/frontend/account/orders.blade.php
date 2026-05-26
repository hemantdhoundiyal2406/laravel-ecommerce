@extends('layouts.frontend')

@section('title', 'My Orders - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <h1 class="mb-4">My orders</h1>
        @include('frontend.account._nav')
        <div class="mini-panel p-4">
            @include('frontend.account.orders-table', ['orders' => $orders])
            <div class="mt-3">{{ $orders->links() }}</div>
        </div>
    </section>
@endsection
