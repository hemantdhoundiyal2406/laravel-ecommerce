@extends('layouts.frontend')

@section('title', 'Addresses - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <h1 class="mb-4">Saved addresses</h1>
        @include('frontend.account._nav')
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="mini-panel p-4">
                    <h2 class="h4">Add address</h2>
                    <form action="{{ route('account.addresses.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Type</label><select name="type" class="form-select"><option value="shipping">Shipping</option><option value="billing">Billing</option></select></div>
                            <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Country</label><input name="country" class="form-control" value="India" required></div>
                            <div class="col-12"><label class="form-label">Address line 1</label><input name="address_line1" class="form-control" required></div>
                            <div class="col-12"><label class="form-label">Address line 2</label><input name="address_line2" class="form-control"></div>
                            <div class="col-md-4"><label class="form-label">City</label><input name="city" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">State</label><input name="state" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Postal</label><input name="postal_code" class="form-control" required></div>
                            <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_default" value="1"> <span class="form-check-label">Default address</span></label></div>
                            <div class="col-12"><button class="btn btn-brand" type="submit">Save Address</button></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    @forelse ($addresses as $address)
                        <div class="col-md-6">
                            <div class="mini-panel p-3 h-100">
                                <div class="d-flex justify-content-between"><strong>{{ ucfirst($address->type) }}</strong>@if($address->is_default)<span class="badge text-bg-success">Default</span>@endif</div>
                                <div class="mt-2">{{ $address->name }}<br>{{ $address->phone }}<br>{{ $address->address_line1 }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</div>
                                <form action="{{ route('account.addresses.delete', $address) }}" method="POST" class="mt-3">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" type="submit">Delete</button></form>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="empty-state">No saved addresses.</div></div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
