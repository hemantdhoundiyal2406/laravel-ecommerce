# UrbanCart - Advanced Laravel E-commerce Website

UrbanCart ek complete Laravel e-commerce project hai jisme Bootstrap 5 frontend, responsive UI, admin panel, authentication, products, variants, cart, coupons, checkout, orders, reviews, contact queries, newsletter, website settings aur SMTP email flows implemented hain.

> Version note: Laravel 13.x current latest major stable line hai, lekin Laravel 13 PHP 8.3+ require karta hai. Is machine par PHP 8.2.12 installed tha, isliye project Laravel 12.x par banaya gaya hai, jo PHP 8.2 compatible stable branch hai. Reference: https://laravel.com/docs/releases

## Tech Stack

- Laravel 12.x
- PHP 8.2+
- MySQL database: `e-commerce`
- Bootstrap 5.3 CDN
- Font Awesome 6
- Google Fonts: Inter + Playfair Display
- JavaScript/jQuery
- Laravel Mail SMTP
- Eloquent ORM, migrations, seeders, middleware, controllers, Blade templates

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed
php artisan serve
```

Open:

- Frontend: http://127.0.0.1:8000
- Admin: http://127.0.0.1:8000/admin

Demo logins:

- Admin: `admin@example.com` / `password`
- Customer: `customer@example.com` / `password`

## Database Setup

Create MySQL database with backticks because the name contains a hyphen:

```sql
CREATE DATABASE `e-commerce` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

`.env` database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e-commerce
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate:fresh --seed
```

Seeder adds:

- Admin account
- Customer account
- Categories
- Brands
- Products with images and variants
- Reviews
- Banners
- Coupons
- Website settings

## SMTP Setup

`.env` mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_SCHEME=null
MAIL_FROM_ADDRESS=support@urbancart.test
MAIL_FROM_NAME="${APP_NAME}"
```

For Gmail, use an app password:

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=your_email@gmail.com
```

Emails are sent for:

- Welcome email after registration
- Forgot/reset password through Laravel password broker
- Customer order confirmation
- Admin new order notification
- Customer order status update
- Contact form admin notification
- Contact form thank-you email
- Newsletter subscription confirmation

## Main Modules

Frontend:

- Home page with hero slider, categories, featured products, offers, brands, testimonials, newsletter
- Product listing with search, category, brand, price, rating, size, color, availability, sorting, grid/list, pagination
- Product detail with gallery, variants, quantity, cart, buy now, wishlist, reviews, related products
- Cart with quantity update, remove, coupon, tax, shipping, totals
- Checkout with billing/shipping, same-as-billing, COD, online payment placeholder
- Customer dashboard, profile, password, orders, tracking, wishlist, addresses
- Contact and static policy pages

Admin:

- Dashboard with sales stats, chart, recent orders, low stock
- Category CRUD with parent/subcategory support
- Brand CRUD
- Product CRUD with multiple images, variants, SEO, flags
- Order management with status/payment/tracking/admin note/invoice
- Coupon CRUD
- Customer management and active/inactive status
- Review approval/rejection
- Contact query read/replied/delete
- Newsletter subscribers and CSV export
- Banner CRUD
- Website settings including tax, shipping, social links, SMTP reference fields

## Important Tables

- `users`: admin/customer accounts, role and status
- `categories`: categories and subcategories
- `brands`: product brands
- `products`: product catalog, pricing, SEO, stock, flags
- `product_images`: multiple product images
- `product_variants`: size/color variant pricing and stock
- `carts`: guest/user cart rows
- `wishlists`: customer wishlist products
- `orders`: order header, customer data, totals, status
- `order_items`: ordered products snapshot
- `payments`: payment method/status/transaction placeholder
- `coupons`: percentage/fixed discount rules
- `reviews`: product reviews and moderation status
- `contact_queries`: contact form submissions
- `newsletters`: subscribed emails
- `banners`: homepage hero banners
- `settings`: website, tax, shipping, social, SMTP reference values
- `addresses`: saved customer addresses

## Controller Map

Frontend controllers:

- `Frontend\AuthController`: register, login, logout, forgot/reset password
- `Frontend\HomeController`: homepage data
- `Frontend\ProductController`: listing, filters, detail page
- `Frontend\CartController`: add/update/remove cart, coupon
- `Frontend\CheckoutController`: order placement, payments, stock deduction, emails
- `Frontend\AccountController`: customer dashboard, profile, orders, wishlist, addresses
- `Frontend\ContactController`: contact form and emails
- `Frontend\NewsletterController`: subscription
- `Frontend\ReviewController`: review submission
- `Frontend\WishlistController`: wishlist toggle
- `Frontend\StaticPageController`: static pages

Admin controllers:

- `Admin\DashboardController`: stats and chart
- `Admin\CategoryController`: category CRUD
- `Admin\BrandController`: brand CRUD
- `Admin\ProductController`: product/images/variants/SEO CRUD
- `Admin\OrderController`: order list, detail, status update, invoice
- `Admin\CouponController`: coupon CRUD
- `Admin\CustomerController`: customer list/detail/status
- `Admin\ReviewController`: review moderation
- `Admin\ContactQueryController`: query management
- `Admin\NewsletterController`: subscribers and export
- `Admin\BannerController`: homepage slider CRUD
- `Admin\SettingController`: website settings

## Order Flow

1. Customer adds product to cart.
2. Cart stores `user_id` for logged-in customers or `session_id` for guests.
3. Customer applies coupon if valid.
4. Checkout calculates subtotal, discount, tax, shipping and grand total.
5. Order is created in `orders`.
6. Cart rows become `order_items`.
7. Payment row is created in `payments`.
8. Product and variant stock is reduced.
9. Coupon usage count increases.
10. Cart is cleared.
11. Customer receives confirmation email.
12. Admin receives new order email.
13. Admin updates order status.
14. Customer receives status update email.

## Payment Flow

Working payment method:

- Cash on Delivery

Online payment:

- Placeholder is already present in checkout and `payments.payload`.
- Integrate Razorpay/Stripe by replacing placeholder logic in `CheckoutController@placeOrder`.

## Security

- Admin routes protected by `admin` middleware
- Customer routes protected by `customer` middleware
- Password hashing through Laravel
- CSRF protection on forms
- Laravel validation on major forms
- Image upload validation
- Eloquent ORM for SQL injection protection
- Unauthorized admin/customer access blocked

## Useful Commands

```bash
php artisan route:list
php artisan test
php artisan view:clear
php artisan cache:clear
php artisan storage:link
```

## Testing

Feature tests cover:

- Homepage and product listing load with seed data
- Admin login and dashboard access
- Customer COD checkout and order creation

Run:

```bash
php artisan test
```

## Interview Project Summary

UrbanCart is a full-stack Laravel e-commerce application built with a clean MVC structure. The frontend uses Bootstrap 5 and Blade components for a responsive store experience. The backend admin panel manages catalog, orders, customers, coupons, reviews, banners, newsletters, settings, and contact queries. The database is normalized with separate product, variant, image, cart, wishlist, order, payment, review, and settings tables. Authentication is role-based using one users table with `admin` and `customer` roles. Checkout uses a service-style cart calculator, validates customer details, creates order records inside a database transaction, deducts stock, stores payment status, clears cart, and sends SMTP emails. The project demonstrates Laravel migrations, seeders, middleware, Eloquent relationships, validation, Blade layouts, mailables, and feature testing.
