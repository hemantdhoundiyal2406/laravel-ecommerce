<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cartService)
    {
        $coupon = session('coupon_id') ? Coupon::find(session('coupon_id')) : null;

        return view('frontend.cart', $cartService->totals($coupon) + ['coupon' => $coupon]);
    }

    public function add(Request $request, CartService $cartService, Product $product)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $variant = isset($data['variant_id']) ? ProductVariant::where('product_id', $product->id)->find($data['variant_id']) : null;

        if ($product->stock_quantity <= 0 || ($variant && $variant->stock_quantity <= 0)) {
            return back()->with('error', 'Selected product is out of stock.');
        }

        $cartService->add($product, $variant, (int) $data['quantity']);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout.index')->with('success', 'Product added. Complete checkout now.');
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'quantities' => ['required', 'array'],
            'quantities.*' => ['integer', 'min:1', 'max:99'],
        ]);

        $items = $cartService->items()->keyBy('id');

        foreach ($data['quantities'] as $cartId => $quantity) {
            if ($items->has((int) $cartId)) {
                $items[(int) $cartId]->update(['quantity' => $quantity]);
            }
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(CartService $cartService, int $cart)
    {
        $item = $cartService->items()->firstWhere('id', $cart);

        if ($item) {
            $item->delete();
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request, CartService $cartService)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:50']]);
        $coupon = Coupon::where('code', strtoupper($data['code']))->first();
        $subtotal = $cartService->totals()['subtotal'];

        if (! $coupon || ! $coupon->isUsableFor($subtotal)) {
            return back()->with('error', 'Coupon is invalid, expired, or not applicable.');
        }

        session(['coupon_id' => $coupon->id]);

        return back()->with('success', 'Coupon applied successfully.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_id');

        return back()->with('success', 'Coupon removed.');
    }
}
