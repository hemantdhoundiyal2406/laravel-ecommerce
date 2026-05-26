@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf @if($product->exists) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-xl-8">
                <div class="admin-card p-4 mb-4">
                    <h2 class="h5">Product details</h2>
                    <div class="row g-3">
                        <div class="col-md-8"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name', $product->name) }}" required></div>
                        <div class="col-md-4"><label class="form-label">SKU</label><input name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control" value="{{ old('slug', $product->slug) }}"></div>
                        <div class="col-md-3"><label class="form-label">Category</label><select name="category_id" class="form-select" required><option value="">Select</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                        <div class="col-md-3"><label class="form-label">Brand</label><select name="brand_id" class="form-select"><option value="">None</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
                        <div class="col-md-4"><label class="form-label">Regular price</label><input type="number" step="0.01" name="regular_price" class="form-control" value="{{ old('regular_price', $product->regular_price) }}" required></div>
                        <div class="col-md-4"><label class="form-label">Sale price</label><input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}"></div>
                        <div class="col-md-4"><label class="form-label">Stock</label><input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required></div>
                        <div class="col-12"><label class="form-label">Short description</label><textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea></div>
                        <div class="col-12"><label class="form-label">Long description</label><textarea name="description" class="form-control" rows="6">{{ old('description', $product->description) }}</textarea></div>
                        <div class="col-12"><label class="form-label">Specifications</label><textarea name="specifications_text" class="form-control" rows="5" placeholder="Material: Cotton&#10;Warranty: 12 months">{{ old('specifications_text', collect($product->specifications ?: [])->map(fn($v, $k) => $k.': '.$v)->implode("\n")) }}</textarea></div>
                    </div>
                </div>
                <div class="admin-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Variants</h2>
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-add-variant-row="#variantRows"><i class="fa-solid fa-plus me-1"></i>Add Row</button>
                    </div>
                    <div id="variantRows" class="d-grid gap-2">
                        @forelse($product->variants as $variant)
                            @include('admin.products.variant-row', ['variant' => $variant])
                        @empty
                            @include('admin.products.variant-row', ['variant' => null])
                        @endforelse
                    </div>
                    <template id="variant-row-template">@include('admin.products.variant-row', ['variant' => null])</template>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="admin-card p-4 mb-4">
                    <h2 class="h5">Images</h2>
                    <input type="file" name="images[]" class="form-control mb-3" accept="image/*" multiple>
                    <label class="form-label">Image URLs, one per line</label>
                    <textarea name="image_urls" class="form-control" rows="4">{{ old('image_urls') }}</textarea>
                    @if($product->exists)
                        <div class="d-flex flex-wrap gap-2 mt-3">@foreach($product->images as $image)<img class="thumb" src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/'.$image->image_path) }}" alt="Product image">@endforeach</div>
                    @endif
                </div>
                <div class="admin-card p-4 mb-4">
                    <h2 class="h5">Flags & status</h2>
                    @foreach(['is_featured' => 'Featured product', 'is_best_seller' => 'Best seller', 'is_new_arrival' => 'New arrival', 'is_active' => 'Active'] as $field => $label)
                        <label class="form-check mb-2"><input class="form-check-input" type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $product->{$field} ?? true))> <span class="form-check-label">{{ $label }}</span></label>
                    @endforeach
                </div>
                <div class="admin-card p-4">
                    <h2 class="h5">SEO</h2>
                    <label class="form-label">SEO title</label><input name="seo_title" class="form-control mb-3" value="{{ old('seo_title', $product->seo_title) }}">
                    <label class="form-label">SEO description</label><textarea name="seo_description" class="form-control mb-3" rows="3">{{ old('seo_description', $product->seo_description) }}</textarea>
                    <label class="form-label">SEO keywords</label><textarea name="seo_keywords" class="form-control mb-3" rows="2">{{ old('seo_keywords', $product->seo_keywords) }}</textarea>
                    <button class="btn btn-success w-100" type="submit">Save Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection
