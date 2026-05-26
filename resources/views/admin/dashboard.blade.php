@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Total Sales', 'value' => 'Rs. '.number_format($totalSales, 2), 'icon' => 'fa-indian-rupee-sign'],
            ['label' => 'Orders', 'value' => $totalOrders, 'icon' => 'fa-receipt'],
            ['label' => 'Customers', 'value' => $totalCustomers, 'icon' => 'fa-users'],
            ['label' => 'Products', 'value' => $totalProducts, 'icon' => 'fa-box'],
            ['label' => 'Pending Orders', 'value' => $pendingOrders, 'icon' => 'fa-clock'],
            ['label' => 'Completed Orders', 'value' => $completedOrders, 'icon' => 'fa-circle-check'],
        ] as $stat)
            <div class="col-md-6 col-xl-4">
                <div class="admin-card p-3 d-flex gap-3 align-items-center">
                    <span class="stat-icon"><i class="fa-solid {{ $stat['icon'] }}"></i></span>
                    <div><div class="text-muted small">{{ $stat['label'] }}</div><div class="h4 mb-0">{{ $stat['value'] }}</div></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="admin-card p-4">
                <h2 class="h5">Sales chart</h2>
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="admin-card p-4 h-100">
                <h2 class="h5">Low stock products</h2>
                <div class="d-grid gap-2">
                    @forelse ($lowStockProducts as $product)
                        <div class="d-flex justify-content-between gap-2 border-bottom pb-2">
                            <span>{{ $product->name }}</span>
                            <strong>{{ $product->stock_quantity }}</strong>
                        </div>
                    @empty
                        <div class="text-muted">No low stock products.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-card p-4">
                <h2 class="h5">Recent orders</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>Rs. {{ number_format($order->grand_total, 2) }}</td>
                                <td><span class="badge text-bg-light border">{{ ucfirst($order->status) }}</span></td>
                                <td><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted text-center py-4">No orders yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById("salesChart");
if (ctx) {
    new Chart(ctx, {
        type: "line",
        data: {
            labels: @json($chartLabels),
            datasets: [{ label: "Sales", data: @json($chartData), borderColor: "#14532d", backgroundColor: "rgba(20,83,45,.12)", fill: true, tension: .35 }]
        }
    });
}
</script>
@endpush
