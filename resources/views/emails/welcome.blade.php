@extends('emails._layout')

@section('body')
    <h1 style="margin-top:0;">Welcome, {{ $user->name }}!</h1>
    <p>Your UrbanCart account is ready. You can now save addresses, build a wishlist, place orders, and track them from your dashboard.</p>
    <p><a href="{{ route('products.index') }}" style="background:#14532d;color:#fff;padding:10px 16px;border-radius:6px;text-decoration:none;">Start shopping</a></p>
@endsection
