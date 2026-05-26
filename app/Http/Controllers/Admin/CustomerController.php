<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')->orWhere('email', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        return view('admin.customers.show', [
            'customer' => $customer->load('orders.items', 'addresses'),
        ]);
    }

    public function updateStatus(Request $request, User $customer)
    {
        $data = $request->validate(['status' => ['required', 'in:active,inactive']]);
        $customer->update($data);

        return back()->with('success', 'Customer status updated.');
    }
}
