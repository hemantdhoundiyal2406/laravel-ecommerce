@extends('layouts.frontend')

@section('title', 'UrbanCart - Premium E-commerce Store')

@section('content')
    <section>
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @forelse ($banners as $banner)
                    <div class="carousel-item @if($loop->first) active @endif">
                        <div class="hero-slide" style="background-image: url('{{ $banner->image ?: asset('assets/images/product-placeholder.svg') }}')">
                            <div class="container">
                                <div class="hero-content">
                                    <span class="badge badge-soft mb-3">Modern Laravel Commerce</span>
                                    <h1 class="display-4 fw-bold mb-3">{{ $banner->title }}</h1>
                                    <p class="lead mb-4">{{ $banner->subtitle }}</p>
                                    @if ($banner->button_text)
                                        <a href="{{ $banner->button_link ?: route('products.index') }}" class="btn btn-accent btn-lg">{{ $banner->button_text }} <i class="fa-solid fa-arrow-right ms-2"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active">
                        <div class="hero-slide" style="background-image: url('{{ asset('assets/images/product-placeholder.svg') }}')">
                            <div class="container"><div class="hero-content"><h1 class="display-4">UrbanCart</h1></div></div>
                        </div>
                    </div>
                @endforelse
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous"><span class="carousel-control-prev-icon"></span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next"><span class="carousel-control-next-icon"></span></button>
        </div>
    </section>

    <section class="container py-5">
        <div class="section-title">
            <div>
                <span class="text-uppercase small text-muted fw-bold">Featured Categories</span>
                <h2 class="mb-0">Shop by department</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">View All</a>
        </div>
        <div class="row g-3">
            @foreach ($categories as $category)
                <div class="col-6 col-md-4 col-lg">
                    <a class="category-tile d-block h-100 text-dark overflow-hidden" href="{{ route('products.index', ['category' => $category->slug]) }}">
                        <img src="{{ str_starts_with($category->image ?? '', 'http') ? $category->image : ($category->image ? asset('storage/'.$category->image) : asset('assets/images/product-placeholder.svg')) }}" alt="{{ $category->name }}">
                        <div class="p-3">
                            <h3 class="h6 mb-1">{{ $category->name }}</h3>
                            <span class="small text-muted">{{ $category->products_count ?? $category->products()->count() }} products</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container pb-5">
        <div class="section-title">
            <div><span class="text-uppercase small text-muted fw-bold">Featured</span><h2 class="mb-0">Best picks for you</h2></div>
            <a href="{{ route('products.index', ['sort' => 'popularity']) }}" class="btn btn-outline-secondary btn-sm">Popular Products</a>
        </div>
        <div class="row g-4">
            @forelse ($featuredProducts as $product)
                <div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>
            @empty
                <div class="col-12"><div class="empty-state">No featured products yet.</div></div>
            @endforelse
        </div>
    </section>

    <section class="py-5" style="background:#eef7f0">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="text-uppercase small text-muted fw-bold">Limited Offer</span>
                    <h2 class="display-6 fw-bold">Use WELCOME10 and save on your first order</h2>
                    <p class="text-muted mb-0">Coupon engine supports percentage/fixed discounts, minimum order rules, expiry dates, and usage limits.</p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="{{ route('products.index') }}" class="btn btn-brand btn-lg">Start Shopping</a>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="section-title"><div><span class="text-uppercase small text-muted fw-bold">Best Selling</span><h2 class="mb-0">Customer favorites</h2></div></div>
                <div class="row g-3">
                    @foreach ($bestSellers->take(4) as $product)
                        <div class="col-6">@include('partials.product-card', ['product' => $product])</div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-title"><div><span class="text-uppercase small text-muted fw-bold">New Arrivals</span><h2 class="mb-0">Fresh in store</h2></div></div>
                <div class="row g-3">
                    @foreach ($newArrivals->take(4) as $product)
                        <div class="col-6">@include('partials.product-card', ['product' => $product])</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="container pb-5">
        <div class="section-title">
            <div><span class="text-uppercase small text-muted fw-bold">Brands</span><h2 class="mb-0">Featured brands</h2></div>
        </div>
        <div class="row g-3">
            @foreach ($brands as $brand)
                <div class="col-6 col-md-3 col-lg">
                    <a href="{{ route('products.index', ['brand[]' => $brand->slug]) }}" class="mini-panel d-block p-4 text-center h-100">
                        <i class="fa-solid fa-award fa-2x mb-2 text-success"></i>
                        <div class="fw-bold text-dark">{{ $brand->name }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container pb-5">
        <div class="section-title">
            <div><span class="text-uppercase small text-muted fw-bold">Reviews</span><h2 class="mb-0">What customers say</h2></div>
        </div>
        <div class="row g-3">
            @forelse ($testimonials as $review)
                <div class="col-md-4">
                    <div class="review-card p-4 h-100">
                        <div class="rating mb-2">@for($i = 1; $i <= 5; $i++)<i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>@endfor</div>
                        <p class="mb-3">{{ $review->comment }}</p>
                        <div class="small fw-bold">{{ $review->name }}</div>
                        <div class="small text-muted">{{ $review->product?->name }}</div>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-state">Customer reviews will appear here.</div></div>
            @endforelse
        </div>
    </section>
@endsection
