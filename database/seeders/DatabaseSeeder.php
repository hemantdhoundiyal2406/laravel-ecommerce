<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('SEED_ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('SEED_ADMIN_PASSWORD', 'password');

        $admin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Store Admin',
                'phone' => '9999999999',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make($adminPassword),
            ]
        );

        $customer = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'phone' => '8888888888',
                'role' => 'customer',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );

        $settings = [
            'site_name' => 'UrbanCart',
            'site_email' => 'support@urbancart.test',
            'site_phone' => '+91 98765 43210',
            'site_address' => 'MG Road, Bengaluru, Karnataka',
            'footer_text' => 'Premium everyday commerce for fashion, electronics, home, and lifestyle.',
            'tax_rate' => '18',
            'shipping_charge' => '79',
            'free_shipping_minimum' => '2999',
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'x_url' => 'https://x.com',
            'admin_email' => $adminEmail,
        ];

        foreach ($settings as $key => $value) {
            Setting::putValue($key, $value);
        }

        $categories = collect([
            ['name' => 'Fashion', 'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Electronics', 'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Home Living', 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Beauty', 'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Sports', 'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80'],
        ])->map(function (array $category, int $index) {
            return Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'image' => $category['image'],
                    'description' => 'Curated '.$category['name'].' products for modern shoppers.',
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        });

        $brands = collect(['Nova', 'Aster', 'Pulse', 'Vero', 'LuxeLine'])->map(function (string $name) {
            return Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'logo' => null,
                    'is_active' => true,
                ]
            );
        });

        $products = [
            ['name' => 'AeroFit Running Shoes', 'category' => 'Sports', 'brand' => 'Pulse', 'price' => 4299, 'sale' => 3499, 'stock' => 28, 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => true, 'best' => true, 'new' => true]],
            ['name' => 'Aurora Wireless Headphones', 'category' => 'Electronics', 'brand' => 'Nova', 'price' => 6999, 'sale' => 5499, 'stock' => 16, 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => true, 'best' => true, 'new' => false]],
            ['name' => 'Serene Ceramic Dinner Set', 'category' => 'Home Living', 'brand' => 'Aster', 'price' => 3199, 'sale' => 2599, 'stock' => 12, 'image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => false, 'best' => true, 'new' => true]],
            ['name' => 'Luxe Hydration Skincare Kit', 'category' => 'Beauty', 'brand' => 'LuxeLine', 'price' => 2499, 'sale' => 1999, 'stock' => 35, 'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => true, 'best' => false, 'new' => true]],
            ['name' => 'Minimal Cotton Overshirt', 'category' => 'Fashion', 'brand' => 'Vero', 'price' => 2199, 'sale' => 1599, 'stock' => 40, 'image' => 'https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => true, 'best' => true, 'new' => false]],
            ['name' => 'Smart Desk LED Lamp', 'category' => 'Electronics', 'brand' => 'Nova', 'price' => 2999, 'sale' => null, 'stock' => 8, 'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => false, 'best' => false, 'new' => true]],
            ['name' => 'Travel Weekender Bag', 'category' => 'Fashion', 'brand' => 'Aster', 'price' => 3799, 'sale' => 2999, 'stock' => 22, 'image' => 'https://images.unsplash.com/photo-1542295669297-4d352b042bca?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => true, 'best' => false, 'new' => true]],
            ['name' => 'Core Yoga Mat Pro', 'category' => 'Sports', 'brand' => 'Pulse', 'price' => 1899, 'sale' => 1499, 'stock' => 31, 'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?auto=format&fit=crop&w=900&q=80', 'flags' => ['featured' => false, 'best' => true, 'new' => false]],
        ];

        foreach ($products as $index => $item) {
            $category = $categories->firstWhere('name', $item['category']);
            $brand = $brands->firstWhere('name', $item['brand']);
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'name' => $item['name'],
                    'sku' => 'SKU-'.str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
                    'regular_price' => $item['price'],
                    'sale_price' => $item['sale'],
                    'stock_quantity' => $item['stock'],
                    'short_description' => 'Premium quality '.$item['name'].' with fast delivery and easy returns.',
                    'description' => 'Designed for daily use, this product balances durable materials, polished finishing, and practical features. It is ready for modern customers who want dependable quality without friction.',
                    'specifications' => [
                        'Material' => 'Premium grade',
                        'Warranty' => '12 months',
                        'Delivery' => '2-5 business days',
                        'Return' => '7 days easy return',
                    ],
                    'is_featured' => $item['flags']['featured'],
                    'is_best_seller' => $item['flags']['best'],
                    'is_new_arrival' => $item['flags']['new'],
                    'is_active' => true,
                    'seo_title' => $item['name'].' | UrbanCart',
                    'seo_description' => 'Buy '.$item['name'].' online at UrbanCart.',
                    'seo_keywords' => strtolower($item['category'].', '.$item['brand'].', '.$item['name']),
                ]
            );

            $product->images()->delete();
            $product->images()->createMany([
                ['image_path' => $item['image'], 'is_primary' => true, 'sort_order' => 1],
                ['image_path' => $item['image'].'&sat=-20', 'is_primary' => false, 'sort_order' => 2],
            ]);

            $product->variants()->delete();
            $product->variants()->createMany([
                ['size' => 'S', 'color' => 'Black', 'color_code' => '#111827', 'price_adjustment' => 0, 'stock_quantity' => 10],
                ['size' => 'M', 'color' => 'Navy', 'color_code' => '#1e3a8a', 'price_adjustment' => 100, 'stock_quantity' => 8],
                ['size' => 'L', 'color' => 'Olive', 'color_code' => '#4d7c0f', 'price_adjustment' => 150, 'stock_quantity' => 6],
            ]);

            Review::updateOrCreate(
                ['product_id' => $product->id, 'email' => 'customer@example.com'],
                [
                    'user_id' => $customer->id,
                    'name' => $customer->name,
                    'rating' => rand(4, 5),
                    'comment' => 'Great quality and smooth delivery experience.',
                    'status' => 'approved',
                ]
            );
        }

        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'],
            ['discount_type' => 'percentage', 'discount_value' => 10, 'minimum_order_amount' => 999, 'usage_limit' => 100, 'used_count' => 0, 'expires_at' => now()->addMonths(6), 'is_active' => true]
        );

        Coupon::updateOrCreate(
            ['code' => 'FLAT500'],
            ['discount_type' => 'fixed', 'discount_value' => 500, 'minimum_order_amount' => 3999, 'usage_limit' => 50, 'used_count' => 0, 'expires_at' => now()->addMonths(3), 'is_active' => true]
        );

        foreach ([
            ['title' => 'Premium Deals For Everyday Shopping', 'subtitle' => 'Fresh arrivals, trusted brands, and fast checkout in one modern Laravel store.', 'button_text' => 'Shop Now', 'button_link' => '/products', 'image' => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&w=1600&q=85'],
            ['title' => 'New Season Essentials', 'subtitle' => 'Upgrade your wardrobe, desk, and home with hand-picked products.', 'button_text' => 'Explore Collection', 'button_link' => '/products?sort=latest', 'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1600&q=85'],
        ] as $index => $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner + ['is_active' => true, 'sort_order' => $index + 1]
            );
        }

        $customer->addresses()->updateOrCreate(
            ['type' => 'shipping'],
            [
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address_line1' => 'Demo Apartments, 12 Market Street',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'postal_code' => '560001',
                'country' => 'India',
                'is_default' => true,
            ]
        );
    }
}
