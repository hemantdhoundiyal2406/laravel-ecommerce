@extends('emails._layout')

@section('body')
    <h1 style="margin-top:0;">New order received</h1>
    <p>Order <strong>{{ $order->order_number }}</strong> was placed by {{ $order->customer_name }}.</p>
    <p>Total: <strong>Rs. {{ number_format($order->grand_total, 2) }}</strong></p>
    <p>Status: {{ ucfirst($order->status) }} | Payment: {{ ucfirst($order->payment_status) }}</p>
@endsection
