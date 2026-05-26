<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isUsableFor(float $subtotal): bool
    {
        if (! $this->is_active || $subtotal < $this->minimum_order_amount) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return ! $this->usage_limit || $this->used_count < $this->usage_limit;
    }

    public function discountFor(float $subtotal): float
    {
        if (! $this->isUsableFor($subtotal)) {
            return 0;
        }

        $discount = $this->discount_type === 'percentage'
            ? $subtotal * ((float) $this->discount_value / 100)
            : (float) $this->discount_value;

        return min($discount, $subtotal);
    }
}
