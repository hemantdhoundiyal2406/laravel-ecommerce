<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        return view('frontend.account.dashboard', [
            'orders' => $user->orders()->latest()->take(5)->get(),
            'wishlistCount' => $user->wishlists()->count(),
            'addressCount' => $user->addresses()->count(),
        ]);
    }

    public function profile()
    {
        return view('frontend.account.profile');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function orders()
    {
        return view('frontend.account.orders', [
            'orders' => auth()->user()->orders()->latest()->paginate(10),
        ]);
    }

    public function orderDetail(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('frontend.account.order-detail', ['order' => $order->load('items', 'payment')]);
    }

    public function wishlist()
    {
        return view('frontend.account.wishlist', [
            'wishlists' => auth()->user()->wishlists()->with('product.primaryImage', 'product.images')->latest()->get(),
        ]);
    }

    public function addresses()
    {
        return view('frontend.account.addresses', [
            'addresses' => auth()->user()->addresses()->latest()->get(),
        ]);
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:billing,shipping'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line1' => ['required', 'string', 'max:190'],
            'address_line2' => ['nullable', 'string', 'max:190'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:80'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] = auth()->id();
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        Address::create($data);

        return back()->with('success', 'Address saved.');
    }

    public function deleteAddress(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();

        return back()->with('success', 'Address deleted.');
    }
}
