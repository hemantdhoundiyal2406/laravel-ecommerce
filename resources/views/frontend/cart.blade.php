@extends('layouts.frontend')

@section('title', 'Shopping Cart - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <div class="section-title">
            <div><span class="text-uppercase small text-muted fw-bold">Cart</span><h1 class="mb-0">Shopping cart</h1></div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
        </div>

        @if ($items->isEmpty())
            <div class="empty-state">
                <i class="fa-solid fa-cart-shopping fa-2x mb-3"></i>
                <h2 class="h4">Your cart is empty</h2>
                <p class="text-muted">Add products to begin checkout.</p>
                <a href="{{ route('products.index') }}" class="btn btn-brand">Shop Products</a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mini-panel p-3">
                        <form id="cartUpdateForm" action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            @method('PATCH')
                        </form>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th style="width:130px">Qty</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-3 align-items-center">
                                                <img src="{{ $item->product->image_url }}" class="rounded" width="72" height="72" style="object-fit:cover" alt="{{ $item->product->name }}">
                                                <div>
                                                    <a class="fw-bold text-dark" href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                                    <div class="small text-muted">{{ $item->variant?->label }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rs. {{ number_format($item->price, 2) }}</td>
                                        <td><input type="number" form="cartUpdateForm" name="quantities[{{ $item->id }}]" class="form-control" value="{{ $item->quantity }}" min="1" max="99"></td>
                                        <td class="fw-bold">Rs. {{ number_format($item->total, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" type="submit" title="Remove"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-brand" form="cartUpdateForm" type="submit">Update Cart</button>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mini-panel p-4">
                        <h2 class="h4 mb-3">Order summary</h2>
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="d-flex gap-2 mb-3">
                            @csrf
                            <input type="text" name="code" class="form-control" placeholder="Coupon code" value="{{ $coupon?->code }}">
                            <button class="btn btn-outline-secondary" type="submit">Apply</button>
                        </form>
                        @if ($coupon)
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="mb-3">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-link p-0" type="submit">Remove {{ $coupon->code }}</button>
                            </form>
                        @endif
                        <div class="d-grid gap-2">
                            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>Rs. {{ number_format($subtotal, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Coupon discount</span><strong>- Rs. {{ number_format($discount, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Shipping</span><strong>Rs. {{ number_format($shipping, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Tax</span><strong>Rs. {{ number_format($tax, 2) }}</strong></div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5"><span>Total</span><strong>Rs. {{ number_format($grand_total, 2) }}</strong></div>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-brand w-100 mt-4">Checkout</a>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
