@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
            <h2 class="h5 mb-0">Product management</h2>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2"><input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search product or SKU"><button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-search"></i></button></form>
                <a href="{{ route('admin.products.create') }}" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i>Add Product</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td><img class="thumb" src="{{ $product->image_url }}" alt="{{ $product->image_alt }}"></td>
                        <td><strong>{{ $product->name }}</strong><div class="small text-muted">{{ $product->sku }}</div></td>
                        <td>{{ $product->category?->name }}<div class="small text-muted">{{ $product->brand?->name }}</div></td>
                        <td>Rs. {{ number_format($product->price, 2) }}</td>
                        <td><span class="badge {{ $product->stock_quantity <= 10 ? 'text-bg-warning' : 'text-bg-light border' }}">{{ $product->stock_quantity }}</span></td>
                        <td><span class="badge {{ $product->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.products.edit', $product) }}"><i class="fa-solid fa-pen"></i></a> <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No products.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </div>
@endsection
