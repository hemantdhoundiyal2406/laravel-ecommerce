<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container my-5 bg-white p-5 border">
    <div class="d-flex justify-content-between mb-4">
        <div><h1 class="h3">Invoice</h1><div>{{ $order->order_number }}</div></div>
        <button onclick="window.print()" class="btn btn-outline-secondary">Print</button>
    </div>
    <div class="row mb-4">
        <div class="col"><strong>Customer</strong><br>{{ $order->customer_name }}<br>{{ $order->customer_email }}<br>{{ $order->customer_phone }}</div>
        <div class="col text-end"><strong>Date</strong><br>{{ $order->created_at->format('d M Y') }}<br><strong>Status</strong><br>{{ ucfirst($order->status) }}</div>
    </div>
    <table class="table">
        <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
        <tbody>@foreach($order->items as $item)<tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>Rs. {{ number_format($item->price, 2) }}</td><td>Rs. {{ number_format($item->total, 2) }}</td></tr>@endforeach</tbody>
    </table>
    <div class="ms-auto" style="max-width:320px">
        <div class="d-flex justify-content-between"><span>Subtotal</span><strong>Rs. {{ number_format($order->subtotal, 2) }}</strong></div>
        <div class="d-flex justify-content-between"><span>Discount</span><strong>- Rs. {{ number_format($order->discount, 2) }}</strong></div>
        <div class="d-flex justify-content-between"><span>Tax</span><strong>Rs. {{ number_format($order->tax, 2) }}</strong></div>
        <div class="d-flex justify-content-between"><span>Shipping</span><strong>Rs. {{ number_format($order->shipping_charge, 2) }}</strong></div>
        <hr><div class="d-flex justify-content-between h5"><span>Total</span><strong>Rs. {{ number_format($order->grand_total, 2) }}</strong></div>
    </div>
</main>
</body>
</html>
