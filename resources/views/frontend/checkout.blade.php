@extends('layouts.frontend')

@section('title', 'Checkout - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <div class="section-title">
            <div><span class="text-uppercase small text-muted fw-bold">Checkout</span><h1 class="mb-0">Complete your order</h1></div>
        </div>

        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mini-panel p-4 mb-4">
                        <h2 class="h4 mb-3">Billing details</h2>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Name</label><input name="billing_name" class="form-control" value="{{ old('billing_name', auth()->user()->name) }}" required></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="billing_email" class="form-control" value="{{ old('billing_email', auth()->user()->email) }}" required></div>
                            <div class="col-md-6"><label class="form-label">Phone</label><input name="billing_phone" class="form-control" value="{{ old('billing_phone', auth()->user()->phone) }}" required></div>
                            <div class="col-md-6"><label class="form-label">Country</label><input name="billing_country" class="form-control" value="{{ old('billing_country', 'India') }}" required></div>
                            <div class="col-12"><label class="form-label">Address line 1</label><input name="billing_address_line1" class="form-control" value="{{ old('billing_address_line1') }}" required></div>
                            <div class="col-12"><label class="form-label">Address line 2</label><input name="billing_address_line2" class="form-control" value="{{ old('billing_address_line2') }}"></div>
                            <div class="col-md-4"><label class="form-label">City</label><input name="billing_city" class="form-control" value="{{ old('billing_city') }}" required></div>
                            <div class="col-md-4"><label class="form-label">State</label><input name="billing_state" class="form-control" value="{{ old('billing_state') }}" required></div>
                            <div class="col-md-4"><label class="form-label">Postal Code</label><input name="billing_postal_code" class="form-control" value="{{ old('billing_postal_code') }}" required></div>
                        </div>
                    </div>

                    <div class="mini-panel p-4 mb-4">
                        <div class="d-flex justify-content-between gap-3 align-items-center mb-3">
                            <h2 class="h4 mb-0">Shipping address</h2>
                            <label class="form-check">
                                <input class="form-check-input" type="checkbox" name="same_as_billing" value="1" data-same-address checked>
                                <span class="form-check-label">Same as billing</span>
                            </label>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 shipping-field d-none"><label class="form-label">Name</label><input name="shipping_name" class="form-control" data-shipping-field value="{{ old('shipping_name') }}"></div>
                            <div class="col-md-6 shipping-field d-none"><label class="form-label">Phone</label><input name="shipping_phone" class="form-control" data-shipping-field value="{{ old('shipping_phone') }}"></div>
                            <div class="col-12 shipping-field d-none"><label class="form-label">Address line 1</label><input name="shipping_address_line1" class="form-control" data-shipping-field value="{{ old('shipping_address_line1') }}"></div>
                            <div class="col-12 shipping-field d-none"><label class="form-label">Address line 2</label><input name="shipping_address_line2" class="form-control" data-shipping-field value="{{ old('shipping_address_line2') }}"></div>
                            <div class="col-md-4 shipping-field d-none"><label class="form-label">City</label><input name="shipping_city" class="form-control" data-shipping-field value="{{ old('shipping_city') }}"></div>
                            <div class="col-md-4 shipping-field d-none"><label class="form-label">State</label><input name="shipping_state" class="form-control" data-shipping-field value="{{ old('shipping_state') }}"></div>
                            <div class="col-md-4 shipping-field d-none"><label class="form-label">Postal Code</label><input name="shipping_postal_code" class="form-control" data-shipping-field value="{{ old('shipping_postal_code') }}"></div>
                            <div class="col-md-6 shipping-field d-none"><label class="form-label">Country</label><input name="shipping_country" class="form-control" data-shipping-field value="{{ old('shipping_country', 'India') }}"></div>
                        </div>
                    </div>

                    <div class="mini-panel p-4">
                        <h2 class="h4 mb-3">Payment method</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-check border rounded p-3 h-100">
                                    <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                                    <span class="form-check-label fw-bold">Cash on Delivery</span>
                                    <span class="d-block small text-muted">Pay when your order arrives.</span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-check border rounded p-3 h-100">
                                    <input class="form-check-input" type="radio" name="payment_method" value="online">
                                    <span class="form-check-label fw-bold">Online Payment</span>
                                    <span class="d-block small text-muted">Gateway placeholder for Razorpay/Stripe integration.</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Order note</label>
                                <textarea name="customer_note" class="form-control" rows="3">{{ old('customer_note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mini-panel p-4 position-sticky" style="top: 105px">
                        <h2 class="h4 mb-3">Order summary</h2>
                        <div class="d-grid gap-3 mb-3">
                            @foreach ($items as $item)
                                <div class="d-flex gap-2">
                                    <img src="{{ $item->product->image_url }}" width="54" height="54" class="rounded" style="object-fit:cover" alt="{{ $item->product->name }}">
                                    <div class="flex-grow-1">
                                        <div class="small fw-bold">{{ $item->product->name }}</div>
                                        <div class="small text-muted">Qty {{ $item->quantity }} {{ $item->variant?->label }}</div>
                                    </div>
                                    <div class="small fw-bold">Rs. {{ number_format($item->total, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-grid gap-2">
                            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>Rs. {{ number_format($subtotal, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Discount</span><strong>- Rs. {{ number_format($discount, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Shipping</span><strong>Rs. {{ number_format($shipping, 2) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Tax</span><strong>Rs. {{ number_format($tax, 2) }}</strong></div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5"><span>Total</span><strong>Rs. {{ number_format($grand_total, 2) }}</strong></div>
                        </div>
                        <button class="btn btn-brand w-100 mt-4" type="submit">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
