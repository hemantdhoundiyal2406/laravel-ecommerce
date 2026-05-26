<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\AdminNewOrderMail;
use App\Mail\OrderPlacedMail;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Support\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index(CartService $cartService)
    {
        $coupon = session('coupon_id') ? Coupon::find(session('coupon_id')) : null;
        $totals = $cartService->totals($coupon);

        if ($totals['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('frontend.checkout', $totals + [
            'coupon' => $coupon,
            'addresses' => Auth::user()?->addresses()->latest()->get() ?? collect(),
        ]);
    }

    public function placeOrder(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'billing_name' => ['required', 'string', 'max:120'],
            'billing_email' => ['required', 'email', 'max:190'],
            'billing_phone' => ['required', 'string', 'max:30'],
            'billing_address_line1' => ['required', 'string', 'max:190'],
            'billing_address_line2' => ['nullable', 'string', 'max:190'],
            'billing_city' => ['required', 'string', 'max:120'],
            'billing_state' => ['required', 'string', 'max:120'],
            'billing_postal_code' => ['required', 'string', 'max:30'],
            'billing_country' => ['required', 'string', 'max:80'],
            'same_as_billing' => ['nullable', 'boolean'],
            'shipping_name' => ['nullable', 'string', 'max:120'],
            'shipping_phone' => ['nullable', 'string', 'max:30'],
            'shipping_address_line1' => ['nullable', 'string', 'max:190'],
            'shipping_address_line2' => ['nullable', 'string', 'max:190'],
            'shipping_city' => ['nullable', 'string', 'max:120'],
            'shipping_state' => ['nullable', 'string', 'max:120'],
            'shipping_postal_code' => ['nullable', 'string', 'max:30'],
            'shipping_country' => ['nullable', 'string', 'max:80'],
            'payment_method' => ['required', 'in:cod,online'],
            'customer_note' => ['nullable', 'string', 'max:500'],
        ]);

        $coupon = session('coupon_id') ? Coupon::find(session('coupon_id')) : null;
        $totals = $cartService->totals($coupon);

        if ($totals['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $billing = $this->addressFrom($data, 'billing');
        $shipping = $request->boolean('same_as_billing') ? $billing : $this->addressFrom($data, 'shipping');

        $order = DB::transaction(function () use ($data, $totals, $coupon, $billing, $shipping) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'coupon_id' => $coupon?->id,
                'order_number' => 'ORD-'.now()->format('Ymd').'-'.str_pad((string) (Order::count() + 1), 5, '0', STR_PAD_LEFT),
                'customer_name' => $data['billing_name'],
                'customer_email' => $data['billing_email'],
                'customer_phone' => $data['billing_phone'],
                'billing_address' => $billing,
                'shipping_address' => $shipping,
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'tax' => $totals['tax'],
                'shipping_charge' => $totals['shipping'],
                'grand_total' => $totals['grand_total'],
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] === 'cod' ? 'pending' : 'awaiting_payment',
                'status' => 'pending',
                'customer_note' => $data['customer_note'] ?? null,
            ]);

            foreach ($totals['items'] as $cartItem) {
                $variantLabel = $cartItem->variant?->label;
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'sku' => $cartItem->product->sku,
                    'variant_label' => $variantLabel,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->total,
                ]);

                $cartItem->product->decrement('stock_quantity', min($cartItem->quantity, $cartItem->product->stock_quantity));
                if ($cartItem->variant) {
                    ProductVariant::whereKey($cartItem->variant->id)->decrement('stock_quantity', min($cartItem->quantity, $cartItem->variant->stock_quantity));
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $data['payment_method'],
                'status' => $data['payment_method'] === 'cod' ? 'pending' : 'placeholder',
                'amount' => $order->grand_total,
                'payload' => ['message' => 'Online gateway placeholder. Integrate Razorpay/Stripe here.'],
            ]);

            if ($coupon) {
                $coupon->increment('used_count');
            }

            $totals['items']->each->delete();

            return $order->load('items', 'payment');
        });

        session()->forget('coupon_id');

        try {
            Mail::to($order->customer_email)->send(new OrderPlacedMail($order));
            Mail::to(Setting::getValue('admin_email', config('mail.from.address')))->send(new AdminNewOrderMail($order));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully.');
    }

    public function success(Order $order)
    {
        abort_unless(! Auth::check() || $order->user_id === Auth::id() || Auth::user()->isAdmin(), 403);

        return view('frontend.order-success', compact('order'));
    }

    private function addressFrom(array $data, string $type): array
    {
        return [
            'name' => $data[$type.'_name'] ?? $data['billing_name'],
            'phone' => $data[$type.'_phone'] ?? $data['billing_phone'],
            'address_line1' => $data[$type.'_address_line1'] ?? $data['billing_address_line1'],
            'address_line2' => $data[$type.'_address_line2'] ?? $data['billing_address_line2'] ?? null,
            'city' => $data[$type.'_city'] ?? $data['billing_city'],
            'state' => $data[$type.'_state'] ?? $data['billing_state'],
            'postal_code' => $data[$type.'_postal_code'] ?? $data['billing_postal_code'],
            'country' => $data[$type.'_country'] ?? $data['billing_country'],
        ];
    }
}
