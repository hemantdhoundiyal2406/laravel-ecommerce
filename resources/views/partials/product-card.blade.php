<div class="product-card h-100 overflow-hidden">
    <a href="{{ route('products.show', $product->slug) }}" class="d-block position-relative">
        <img src="{{ $product->image_url }}" class="product-img" alt="{{ $product->image_alt }}">
        @if ($product->discount_percent)
            <span class="badge text-bg-warning position-absolute top-0 start-0 m-2">{{ $product->discount_percent }}% off</span>
        @endif
    </a>
    <div class="p-3">
        <div class="small text-muted mb-1">{{ $product->brand?->name ?? 'UrbanCart' }}</div>
        <h3 class="h6 mb-2"><a class="text-dark" href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
        <div class="rating small mb-2">
            @for ($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= round($product->average_rating ?: 0) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
            @endfor
            <span class="text-muted ms-1">({{ $product->approvedReviews->count() }})</span>
        </div>
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="price">Rs. {{ number_format($product->price, 2) }}</span>
            @if ($product->sale_price)
                <span class="old-price">Rs. {{ number_format($product->regular_price, 2) }}</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-brand btn-sm w-100" type="submit"><i class="fa-solid fa-cart-plus me-1"></i>Add</button>
            </form>
            @auth
                <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm" type="submit" title="Wishlist"><i class="fa-regular fa-heart"></i></button>
                </form>
            @endauth
        </div>
    </div>
</div>
