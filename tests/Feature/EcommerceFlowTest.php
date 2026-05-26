<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_home_and_product_listing_load_with_seeded_products(): void
    {
        $this->get('/')->assertOk()->assertSee('UrbanCart');
        $this->get('/products')->assertOk()->assertSee('Shop products')->assertSee('products found');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Dashboard');
    }

    public function test_customer_can_place_cod_order(): void
    {
        $customer = User::where('email', 'customer@example.com')->firstOrFail();
        $product = Product::firstOrFail();

        $this->actingAs($customer);

        $this->post(route('cart.add', $product), [
            'quantity' => 1,
        ])->assertSessionHas('success');

        $this->post(route('checkout.place'), [
            'billing_name' => 'Demo Customer',
            'billing_email' => 'customer@example.com',
            'billing_phone' => '8888888888',
            'billing_address_line1' => '12 Market Street',
            'billing_city' => 'Bengaluru',
            'billing_state' => 'Karnataka',
            'billing_postal_code' => '560001',
            'billing_country' => 'India',
            'same_as_billing' => 1,
            'payment_method' => 'cod',
        ])->assertRedirect();

        $this->assertDatabaseCount('orders', 1);
        $this->assertSame('pending', Order::first()->status);
    }
}
