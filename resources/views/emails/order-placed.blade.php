@extends('emails._layout')

@section('body')
    <h1 style="margin-top:0;">Order confirmed</h1>
    <p>Hi {{ $order->customer_name }}, your order <strong>{{ $order->order_number }}</strong> has been placed.</p>
    <table style="width:100%;border-collapse:collapse;">
        @foreach($order->items as $item)
            <tr><td style="padding:8px;border-bottom:1px solid #e5e7eb;">{{ $item->product_name }} x {{ $item->quantity }}</td><td style="padding:8px;border-bottom:1px solid #e5e7eb;text-align:right;">Rs. {{ number_format($item->total, 2) }}</td></tr>
        @endforeach
        <tr><td style="padding:8px;text-align:right;"><strong>Total</strong></td><td style="padding:8px;text-align:right;"><strong>Rs. {{ number_format($order->grand_total, 2) }}</strong></td></tr>
    </table>
@endsection
