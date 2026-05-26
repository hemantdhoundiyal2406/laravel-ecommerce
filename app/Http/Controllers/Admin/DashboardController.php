<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $salesByDay = Order::selectRaw('DATE(created_at) as day, SUM(grand_total) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.dashboard', [
            'totalSales' => Order::whereIn('status', ['processing', 'shipped', 'delivered'])->sum('grand_total'),
            'totalOrders' => Order::count(),
            'totalCustomers' => User::where('role', 'customer')->count(),
            'totalProducts' => Product::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'completedOrders' => Order::where('status', 'delivered')->count(),
            'recentOrders' => Order::latest()->take(8)->get(),
            'lowStockProducts' => Product::where('stock_quantity', '<=', 10)->orderBy('stock_quantity')->take(8)->get(),
            'chartLabels' => $salesByDay->pluck('day'),
            'chartData' => $salesByDay->pluck('total'),
        ]);
    }
}
