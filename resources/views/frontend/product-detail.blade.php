@extends('layouts.frontend')

@section('title', $product->seo_title ?: $product->name.' - UrbanCart')
@section('meta_description', $product->seo_description ?: $product->short_description)
@section('meta_keywords', $product->seo_keywords)
@section('meta_image', $product->image_url)
@section('og_type', 'product')

@section('content')
    <section class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-6">
                <img id="mainProductImage" src="{{ $product->image_url }}" class="img-fluid rounded border w-100" style="aspect-ratio:1/1;object-fit:cover" alt="{{ $product->image_alt }}">
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    @foreach ($product->images as $image)
                        <img src="{{ \App\Support\Seo::url($image->image_path) }}" class="gallery-thumb" alt="{{ $image->alt }}" data-gallery-thumb="#mainProductImage">
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="small text-muted mb-1">{{ $product->brand?->name }}</div>
                <h1 class="display-6 fw-bold">{{ $product->name }}</h1>
                <div class="rating mb-3">
                    @for($i=1;$i<=5;$i++)<i class="{{ $i <= round($product->average_rating ?: 0) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>@endfor
                    <span class="text-muted small ms-2">{{ $product->approvedReviews->count() }} reviews</span>
                </div>
                <div class="d-flex gap-3 align-items-center mb-3">
                    <span class="display-6 price">Rs. {{ number_format($product->price, 2) }}</span>
                    @if ($product->sale_price)
                        <span class="old-price fs-5">Rs. {{ number_format($product->regular_price, 2) }}</span>
                        <span class="badge text-bg-warning">{{ $product->discount_percent }}% off</span>
                    @endif
                </div>
                <p class="lead text-muted">{{ $product->short_description }}</p>
                <div class="mb-3">
                    @if ($product->stock_quantity > 0)
                        <span class="badge text-bg-success">In stock: {{ $product->stock_quantity }}</span>
                    @else
                        <span class="badge text-bg-danger">Out of stock</span>
                    @endif
                    <span class="badge text-bg-light border">SKU: {{ $product->sku }}</span>
                </div>
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mini-panel p-3 mb-4">
                    @csrf
                    @if ($product->variants->isNotEmpty())
                        <label class="form-label fw-semibold">Variant</label>
                        <div class="row g-2 mb-3">
                            @foreach ($product->variants->where('is_active', true) as $variant)
                                <div class="col-sm-6">
                                    <label class="form-check border rounded p-2 d-flex gap-2 align-items-center">
                                        <input class="form-check-input ms-0" type="radio" name="variant_id" value="{{ $variant->id }}" @checked($loop->first)>
                                        @if ($variant->color_code)<span class="swatch" style="background:{{ $variant->color_code }}"></span>@endif
                                        <span>{{ $variant->label ?: 'Default' }} @if($variant->price_adjustment > 0)+Rs. {{ number_format($variant->price_adjustment, 2) }}@endif</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row g-2 align-items-end">
                        <div class="col-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" value="1" min="1" max="99">
                        </div>
                        <div class="col">
                            <button class="btn btn-brand w-100" type="submit" @disabled($product->stock_quantity <= 0)><i class="fa-solid fa-cart-plus me-1"></i>Add to cart</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-accent w-100" type="submit" name="buy_now" value="1" @disabled($product->stock_quantity <= 0)>Buy now</button>
                        </div>
                    </div>
                </form>
                @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mb-4">
                        @csrf
                        <button class="btn btn-outline-secondary" type="submit"><i class="fa-regular fa-heart me-1"></i>Add to wishlist</button>
                    </form>
                @endauth
                <div class="accordion" id="productInfo">
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#description">Description</button></h2>
                        <div id="description" class="accordion-collapse collapse show" data-bs-parent="#productInfo"><div class="accordion-body">{{ $product->description }}</div></div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#specifications">Specifications</button></h2>
                        <div id="specifications" class="accordion-collapse collapse" data-bs-parent="#productInfo">
                            <div class="accordion-body">
                                <table class="table table-sm">
                                    @foreach (($product->specifications ?: []) as $key => $value)
                                        <tr><th>{{ $key }}</th><td>{{ $value }}</td></tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-4">
            <div class="col-lg-7">
                <h2 class="h3 mb-3">Reviews</h2>
                <div class="d-grid gap-3">
                    @forelse ($product->approvedReviews as $review)
                        <div class="review-card p-3">
                            <div class="rating mb-1">@for($i=1;$i<=5;$i++)<i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>@endfor</div>
                            <p class="mb-1">{{ $review->comment }}</p>
                            <div class="small text-muted">{{ $review->name }} on {{ $review->created_at->format('d M Y') }}</div>
                        </div>
                    @empty
                        <div class="empty-state">No approved reviews yet.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-5">
                <div class="mini-panel p-4">
                    <h2 class="h4">Write a review</h2>
                    @auth
                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select mb-3" required>
                                @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} stars</option>@endfor
                            </select>
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control mb-3" rows="4" required></textarea>
                            <button class="btn btn-brand" type="submit">Submit Review</button>
                        </form>
                    @else
                        <p class="text-muted">Please login to add your review.</p>
                        <a class="btn btn-brand" href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="container pb-5">
        <div class="section-title"><div><span class="text-uppercase small text-muted fw-bold">Related</span><h2 class="mb-0">You may also like</h2></div></div>
        <div class="row g-4">
            @forelse ($related as $product)
                <div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>
            @empty
                <div class="col-12"><div class="empty-state">No related products.</div></div>
            @endforelse
        </div>
    </section>
@endsection
