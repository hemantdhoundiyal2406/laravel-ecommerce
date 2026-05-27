@php
    use App\Models\Category;
    use App\Models\Setting;
    use App\Support\CartService;
    use Illuminate\Support\Str;

    $siteName = Setting::getValue('site_name', 'UrbanCart');
    $navCategories = Category::where('is_active', true)->orderBy('name')->take(8)->get();
    $cartCount = app(CartService::class)->count();
    $pageTitle = trim($__env->yieldContent('title', Setting::getValue('seo_default_title', $siteName)));
    $metaTitle = trim($__env->yieldContent('meta_title', $pageTitle));
    $metaDescription = Str::limit(strip_tags(trim($__env->yieldContent('meta_description', Setting::getValue('seo_default_description', Setting::getValue('footer_text', 'Modern Laravel e-commerce store'))))), 160, '');
    $metaKeywords = trim($__env->yieldContent('meta_keywords', Setting::getValue('seo_default_keywords', '')));
    $metaImage = trim($__env->yieldContent('meta_image', asset('assets/images/product-placeholder.svg')));
    $canonicalUrl = trim($__env->yieldContent('canonical', url()->current()));
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription }}">
    @if($metaKeywords)
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <title>{{ $pageTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="topbar py-2">
    <div class="container d-flex justify-content-between gap-3 small">
        <span><i class="fa-solid fa-truck-fast me-1"></i>Free shipping over Rs. {{ number_format((float) Setting::getValue('free_shipping_minimum', 2999)) }}</span>
        <span class="d-none d-md-inline"><i class="fa-solid fa-phone me-1"></i>{{ Setting::getValue('site_phone', '+91 98765 43210') }}</span>
    </div>
</div>
<header class="site-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}">
                <span class="brand-mark"><i class="fa-solid fa-bag-shopping"></i></span>
                <span>{{ $siteName }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <form action="{{ route('products.index') }}" class="d-flex mx-lg-4 my-3 my-lg-0 flex-grow-1" role="search">
                    <select name="category" class="form-select rounded-end-0 d-none d-md-block" style="max-width: 180px">
                        <option value="">All Categories</option>
                        @foreach ($navCategories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input name="search" value="{{ request('search') }}" class="form-control rounded-0" type="search" placeholder="Search products, SKU, brands">
                    <button class="btn btn-brand rounded-start-0" type="submit" title="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact.index') }}">Contact</a></li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}" title="Cart">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @if ($cartCount)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-warning">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="fa-regular fa-user me-1"></i>{{ auth()->user()->name }}</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('account.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.orders') }}">My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">@csrf<button class="dropdown-item" type="submit">Logout</button></form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="btn btn-brand btn-sm" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>

<main>
    <div class="container mt-3">
        @include('partials.flash')
    </div>
    @yield('content')
</main>

<footer class="footer mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="text-white">{{ $siteName }}</h5>
                <p>{{ Setting::getValue('footer_text', 'Modern Laravel e-commerce store') }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ Setting::getValue('facebook_url', '#') }}" title="Facebook"><i class="fa-brands fa-facebook fa-lg"></i></a>
                    <a href="{{ Setting::getValue('instagram_url', '#') }}" title="Instagram"><i class="fa-brands fa-instagram fa-lg"></i></a>
                    <a href="{{ Setting::getValue('x_url', '#') }}" title="X"><i class="fa-brands fa-x-twitter fa-lg"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white">Company</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><a href="{{ route('static.page', 'about-us') }}">About Us</a></li>
                    <li><a href="{{ route('contact.index') }}">Contact</a></li>
                    <li><a href="{{ route('static.page', 'faq') }}">FAQ</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white">Policies</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><a href="{{ route('static.page', 'privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('static.page', 'terms-and-conditions') }}">Terms</a></li>
                    <li><a href="{{ route('static.page', 'refund-policy') }}">Refund Policy</a></li>
                    <li><a href="{{ route('static.page', 'shipping-policy') }}">Shipping Policy</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white">Newsletter</h6>
                <form action="{{ route('newsletter.store') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="email" name="email" class="form-control" placeholder="Email address" required>
                    <button class="btn btn-accent" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
                <p class="small mt-3 mb-0"><i class="fa-solid fa-location-dot me-1"></i>{{ Setting::getValue('site_address', 'Bengaluru, India') }}</p>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="small d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</span>
            <span>Secure Laravel checkout with CSRF protection.</span>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
