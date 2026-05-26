<?php

namespace App\Support;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function items(): Collection
    {
        return Cart::with(['product.primaryImage', 'product.images', 'variant'])
            ->when(Auth::check(), fn ($query) => $query->where('user_id', Auth::id()))
            ->when(! Auth::check(), fn ($query) => $query->where('session_id', session()->getId()))
            ->latest()
            ->get();
    }

    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    public function add(Product $product, ?ProductVariant $variant, int $quantity): Cart
    {
        $price = $product->price + (float) ($variant?->price_adjustment ?? 0);

        $cart = Cart::firstOrNew([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : session()->getId(),
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ]);

        $cart->quantity = min(($cart->exists ? $cart->quantity : 0) + $quantity, 99);
        $cart->price = $price;
        $cart->save();

        return $cart;
    }

    public function totals(?Coupon $coupon = null): array
    {
        $items = $this->items();
        $subtotal = (float) $items->sum(fn (Cart $item) => $item->total);
        $discount = $coupon?->discountFor($subtotal) ?? 0;
        $taxRate = (float) Setting::getValue('tax_rate', 18);
        $freeMinimum = (float) Setting::getValue('free_shipping_minimum', 2999);
        $shipping = $subtotal > 0 && $subtotal < $freeMinimum ? (float) Setting::getValue('shipping_charge', 79) : 0;
        $tax = max(($subtotal - $discount), 0) * ($taxRate / 100);

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'shipping' => round($shipping, 2),
            'grand_total' => round(max($subtotal - $discount, 0) + $tax + $shipping, 2),
        ];
    }

    public function mergeGuestCartToUser(): void
    {
        if (! Auth::check()) {
            return;
        }

        Cart::where('session_id', session()->getId())->whereNull('user_id')->get()->each(function (Cart $guestItem) {
            $existing = Cart::where('user_id', Auth::id())
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
                $guestItem->delete();

                return;
            }

            $guestItem->update(['user_id' => Auth::id(), 'session_id' => null]);
        });
    }
}
