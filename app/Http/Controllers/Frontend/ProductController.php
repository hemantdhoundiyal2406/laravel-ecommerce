<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = null;
        $selectedBrands = collect();
        $query = Product::with(['category', 'brand', 'primaryImage', 'images', 'approvedReviews'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $selectedCategory = Category::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        if ($request->filled('brand')) {
            $selectedBrands = Brand::whereIn('slug', (array) $request->brand)->get();
            $query->whereHas('brand', fn ($q) => $q->whereIn('slug', (array) $request->brand));
        }

        if ($request->filled('min_price')) {
            $query->whereRaw('COALESCE(sale_price, regular_price) >= ?', [(float) $request->min_price]);
        }

        if ($request->filled('max_price')) {
            $query->whereRaw('COALESCE(sale_price, regular_price) <= ?', [(float) $request->max_price]);
        }

        if ($request->filled('availability')) {
            $request->availability === 'in_stock'
                ? $query->where('stock_quantity', '>', 0)
                : $query->where('stock_quantity', '<=', 0);
        }

        if ($request->filled('rating')) {
            $rating = (int) $request->rating;
            $query->whereHas('approvedReviews', fn ($q) => $q->where('rating', '>=', $rating));
        }

        if ($request->filled('size')) {
            $query->whereHas('variants', fn ($q) => $q->where('size', $request->size)->where('is_active', true));
        }

        if ($request->filled('color')) {
            $query->whereHas('variants', fn ($q) => $q->where('color', 'like', '%'.$request->color.'%')->where('is_active', true));
        }

        match ($request->get('sort')) {
            'price_low' => $query->orderByRaw('COALESCE(sale_price, regular_price) asc'),
            'price_high' => $query->orderByRaw('COALESCE(sale_price, regular_price) desc'),
            'popularity' => $query->orderByDesc('views'),
            default => $query->latest(),
        };

        return view('frontend.products', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'viewMode' => $request->get('view', 'grid'),
            'metaTitle' => $this->listingMetaTitle($selectedCategory, $selectedBrands),
            'metaDescription' => $this->listingMetaDescription($selectedCategory, $selectedBrands),
            'metaKeywords' => $selectedCategory?->seo_keywords ?: ($selectedBrands->count() === 1 ? $selectedBrands->first()->seo_keywords : Setting::getValue('seo_products_keywords')),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'variants', 'approvedReviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product->increment('views');

        $related = Product::with(['primaryImage', 'images', 'approvedReviews'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('frontend.product-detail', compact('product', 'related'));
    }

    private function listingMetaTitle(?Category $category, $brands): string
    {
        if ($category) {
            return $category->seo_title ?: $category->name.' Products - UrbanCart';
        }

        if ($brands->count() === 1) {
            $brand = $brands->first();

            return $brand->seo_title ?: $brand->name.' Products - UrbanCart';
        }

        return Setting::getValue('seo_products_title', 'Products - UrbanCart');
    }

    private function listingMetaDescription(?Category $category, $brands): string
    {
        if ($category) {
            return $category->seo_description ?: $category->description ?: 'Shop '.$category->name.' products online at UrbanCart.';
        }

        if ($brands->count() === 1) {
            $brand = $brands->first();

            return $brand->seo_description ?: 'Shop '.$brand->name.' products online at UrbanCart.';
        }

        return Setting::getValue('seo_products_description', 'Shop latest fashion, electronics, home, beauty, and sports products online.');
    }
}
