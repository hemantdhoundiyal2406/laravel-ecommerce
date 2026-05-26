<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                    ->orWhere('customer_name', 'like', '%'.$request->search.'%')
                    ->orWhere('customer_email', 'like', '%'.$request->search.'%');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', ['order' => $order->load('items', 'payment', 'user')]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
            'payment_status' => ['required', 'in:pending,paid,failed,refunded,awaiting_payment'],
            'tracking_number' => ['nullable', 'string', 'max:120'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $order->status;
        $order->update($data);

        if ($oldStatus !== $order->status) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return back()->with('success', 'Order updated.');
    }

    public function invoice(Order $order)
    {
        return view('admin.orders.invoice', ['order' => $order->load('items', 'payment')]);
    }
}
