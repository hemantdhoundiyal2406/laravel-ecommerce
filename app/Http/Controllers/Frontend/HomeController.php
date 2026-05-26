<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('frontend.home', [
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->take(8)->get(),
            'featuredProducts' => Product::with(['primaryImage', 'images', 'approvedReviews'])->where('is_active', true)->where('is_featured', true)->take(8)->get(),
            'bestSellers' => Product::with(['primaryImage', 'images', 'approvedReviews'])->where('is_active', true)->where('is_best_seller', true)->take(8)->get(),
            'newArrivals' => Product::with(['primaryImage', 'images', 'approvedReviews'])->where('is_active', true)->where('is_new_arrival', true)->latest()->take(8)->get(),
            'brands' => Brand::where('is_active', true)->take(10)->get(),
            'testimonials' => Review::with('product')->where('status', 'approved')->latest()->take(6)->get(),
        ]);
    }
}
