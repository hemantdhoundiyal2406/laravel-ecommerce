@if ($orders->isEmpty())
    <div class="empty-state">No orders yet.</div>
@else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>Rs. {{ number_format($order->grand_total, 2) }}</td>
                    <td><span class="badge text-bg-light border">{{ ucfirst($order->status) }}</span></td>
                    <td><a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">Details</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
