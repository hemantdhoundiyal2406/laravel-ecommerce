@extends('layouts.frontend')

@section('title', 'Wishlist - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <h1 class="mb-4">Wishlist</h1>
        @include('frontend.account._nav')
        <div class="row g-4">
            @forelse ($wishlists as $wishlist)
                <div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $wishlist->product])</div>
            @empty
                <div class="col-12"><div class="empty-state">Your wishlist is empty.</div></div>
            @endforelse
        </div>
    </section>
@endsection
