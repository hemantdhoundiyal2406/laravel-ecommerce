@extends('emails._layout')

@section('body')
    <h1 style="margin-top:0;">Order status updated</h1>
    <p>Your order <strong>{{ $order->order_number }}</strong> is now <strong>{{ ucfirst($order->status) }}</strong>.</p>
    @if($order->tracking_number)
        <p>Tracking number: <strong>{{ $order->tracking_number }}</strong></p>
    @endif
@endsection
