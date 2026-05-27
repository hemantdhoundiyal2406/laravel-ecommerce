<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function seoFields(): array
    {
        return [
            'seo_default' => 'Default / fallback',
            'seo_home' => 'Home page',
            'seo_products' => 'Products listing',
            'seo_contact' => 'Contact page',
            'seo_cart' => 'Cart page',
            'seo_checkout' => 'Checkout page',
            'seo_login' => 'Login page',
            'seo_register' => 'Register page',
            'seo_forgot_password' => 'Forgot password page',
            'seo_page_about_us' => 'About Us page',
            'seo_page_privacy_policy' => 'Privacy Policy page',
            'seo_page_terms_and_conditions' => 'Terms & Conditions page',
            'seo_page_refund_policy' => 'Refund Policy page',
            'seo_page_shipping_policy' => 'Shipping Policy page',
            'seo_page_faq' => 'FAQ page',
        ];
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting.$key", fn () => static::where('key', $key)->value('value') ?? $default);
    }

    public static function putValue(string $key, mixed $value, string $type = 'text'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $type]);
        Cache::forget("setting.$key");
    }
}
