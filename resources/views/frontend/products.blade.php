@extends('layouts.frontend')

@section('title', 'Products - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <div class="section-title">
            <div>
                <span class="text-uppercase small text-muted fw-bold">Catalog</span>
                <h1 class="mb-0">Shop products</h1>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" title="Grid view"><i class="fa-solid fa-table-cells-large"></i></a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" title="List view"><i class="fa-solid fa-list"></i></a>
            </div>
        </div>

        <div class="row g-4">
            <aside class="col-lg-3">
                <form class="mini-panel p-3" method="GET" action="{{ route('products.index') }}">
                    <h2 class="h5 mb-3">Filters</h2>
                    <div class="mb-3">
                        <label class="form-label">Keyword</label>
                        <input type="search" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brands</label>
                        <div class="d-grid gap-2">
                            @foreach ($brands as $brand)
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="brand[]" value="{{ $brand->slug }}" @checked(in_array($brand->slug, (array) request('brand', [])))>
                                    <span class="form-check-label">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label">Min</label>
                            <input type="number" class="form-control" name="min_price" value="{{ request('min_price') }}" min="0">
                        </div>
                        <div class="col">
                            <label class="form-label">Max</label>
                            <input type="number" class="form-control" name="max_price" value="{{ request('max_price') }}" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <select name="rating" class="form-select">
                            <option value="">Any</option>
                            @for ($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}" @selected((string) request('rating') === (string) $rating)>{{ $rating }} stars & up</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Size</label>
                        <select name="size" class="form-select">
                            <option value="">Any</option>
                            @foreach (['S', 'M', 'L', 'XL'] as $size)
                                <option value="{{ $size }}" @selected(request('size') === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="text" class="form-control" name="color" value="{{ request('color') }}" placeholder="Black, Navy">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Availability</label>
                        <select name="availability" class="form-select">
                            <option value="">All</option>
                            <option value="in_stock" @selected(request('availability') === 'in_stock')>In stock</option>
                            <option value="out_of_stock" @selected(request('availability') === 'out_of_stock')>Out of stock</option>
                        </select>
                    </div>
                    <input type="hidden" name="view" value="{{ $viewMode }}">
                    <button class="btn btn-brand w-100" type="submit"><i class="fa-solid fa-filter me-1"></i>Apply Filters</button>
                    <a class="btn btn-link w-100 mt-2" href="{{ route('products.index') }}">Clear</a>
                </form>
            </aside>

            <div class="col-lg-9">
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center mb-3">
                    <div class="text-muted small">{{ $products->total() }} products found</div>
                    <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2">
                        @foreach (request()->except('sort', 'page') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $nested)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $nested }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="latest" @selected(request('sort') === 'latest')>Latest</option>
                            <option value="price_low" @selected(request('sort') === 'price_low')>Price low to high</option>
                            <option value="price_high" @selected(request('sort') === 'price_high')>Price high to low</option>
                            <option value="popularity" @selected(request('sort') === 'popularity')>Popularity</option>
                        </select>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="empty-state">
                        <i class="fa-regular fa-face-smile fa-2x mb-2"></i>
                        <h2 class="h5">No products matched</h2>
                        <p class="mb-0 text-muted">Try removing a filter or changing your search keyword.</p>
                    </div>
                @elseif ($viewMode === 'list')
                    <div class="d-grid gap-3">
                        @foreach ($products as $product)
                            <div class="product-card p-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-4 col-md-3"><img src="{{ $product->image_url }}" class="img-fluid rounded" alt="{{ $product->name }}"></div>
                                    <div class="col">
                                        <h2 class="h5"><a class="text-dark" href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h2>
                                        <p class="text-muted">{{ $product->short_description }}</p>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="price">Rs. {{ number_format($product->price, 2) }}</span>
                                            @if($product->sale_price)<span class="old-price">Rs. {{ number_format($product->regular_price, 2) }}</span>@endif
                                        </div>
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-brand btn-sm">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($products as $product)
                            <div class="col-6 col-xl-4">@include('partials.product-card', ['product' => $product])</div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">{{ $products->links() }}</div>
            </div>
        </div>
    </section>
@endsection
