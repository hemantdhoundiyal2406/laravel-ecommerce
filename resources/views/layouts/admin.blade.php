@php
    use App\Models\Setting;
    $siteName = Setting::getValue('site_name', 'UrbanCart');
    $nav = [
        ['label' => 'Dashboard', 'icon' => 'fa-chart-line', 'route' => 'admin.dashboard'],
        ['label' => 'Categories', 'icon' => 'fa-layer-group', 'route' => 'admin.categories.index'],
        ['label' => 'Brands', 'icon' => 'fa-award', 'route' => 'admin.brands.index'],
        ['label' => 'Products', 'icon' => 'fa-box', 'route' => 'admin.products.index'],
        ['label' => 'Orders', 'icon' => 'fa-receipt', 'route' => 'admin.orders.index'],
        ['label' => 'Coupons', 'icon' => 'fa-ticket', 'route' => 'admin.coupons.index'],
        ['label' => 'Customers', 'icon' => 'fa-users', 'route' => 'admin.customers.index'],
        ['label' => 'Reviews', 'icon' => 'fa-star', 'route' => 'admin.reviews.index'],
        ['label' => 'Contacts', 'icon' => 'fa-envelope', 'route' => 'admin.contacts.index'],
        ['label' => 'Newsletter', 'icon' => 'fa-paper-plane', 'route' => 'admin.newsletters.index'],
        ['label' => 'Banners', 'icon' => 'fa-images', 'route' => 'admin.banners.index'],
        ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => 'admin.settings.index'],
    ];
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body>
<div class="admin-shell d-flex">
    <aside class="admin-sidebar p-3">
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="stat-icon"><i class="fa-solid fa-bag-shopping"></i></span>
            <div>
                <div class="fw-bold text-white">{{ $siteName }}</div>
                <div class="small">Admin Panel</div>
            </div>
        </div>
        <nav class="d-grid gap-1">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'active' : '' }}">
                    <i class="fa-solid {{ $item['icon'] }} fa-fw"></i>{{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <hr class="border-secondary">
        <a href="{{ route('home') }}"><i class="fa-solid fa-store fa-fw"></i>View Store</a>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button class="btn btn-outline-light w-100" type="submit"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</button>
        </form>
    </aside>
    <div class="admin-content p-3 p-lg-4">
        <div class="admin-topbar px-3 py-3 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">@yield('title', 'Dashboard')</h1>
                <div class="small text-muted">Manage products, orders, customers, and website settings.</div>
            </div>
            <div class="text-end small">
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                <div class="text-muted">{{ auth()->user()->email }}</div>
            </div>
        </div>
        @include('partials.flash')
        @yield('content')
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
