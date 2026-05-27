<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand', 'primaryImage'])
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')->orWhere('sku', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data = $this->normalize($request, $data);

        $product = Product::create($data);
        $this->syncImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product->load('images', 'variants'),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);
        $data = $this->normalize($request, $data);

        $product->update($data);
        $this->syncImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if (! str_starts_with($image->image_path, 'http')) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:210', Rule::unique('products', 'slug')->ignore($product)],
            'sku' => ['required', 'string', 'max:80', Rule::unique('products', 'sku')->ignore($product)],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:regular_price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'specifications_text' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'image_urls' => ['nullable', 'string'],
            'variant_sizes' => ['nullable', 'array'],
            'variant_colors' => ['nullable', 'array'],
            'variant_color_codes' => ['nullable', 'array'],
            'variant_adjustments' => ['nullable', 'array'],
            'variant_stocks' => ['nullable', 'array'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function normalize(Request $request, array $data): array
    {
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['brand_id'] = $data['brand_id'] ?? null;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_new_arrival'] = $request->boolean('is_new_arrival');
        $data['is_active'] = $request->boolean('is_active');
        $data['specifications'] = $this->parseSpecifications($request->input('specifications_text'));
        unset($data['specifications_text'], $data['images'], $data['image_urls'], $data['variant_sizes'], $data['variant_colors'], $data['variant_color_codes'], $data['variant_adjustments'], $data['variant_stocks']);

        return $data;
    }

    private function parseSpecifications(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $text))
            ->filter(fn ($line) => str_contains($line, ':'))
            ->mapWithKeys(function ($line) {
                [$key, $value] = array_map('trim', explode(':', $line, 2));

                return [$key => $value];
            })
            ->all();
    }

    private function syncImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images') && ! $request->filled('image_urls')) {
            return;
        }

        foreach ($product->images as $image) {
            if (! str_starts_with($image->image_path, 'http')) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        $product->images()->delete();

        $sort = 1;
        foreach ($request->file('images', []) as $file) {
            $product->images()->create([
                'image_path' => Seo::storeImage($file, 'products'),
                'is_primary' => $sort === 1,
                'sort_order' => $sort++,
            ]);
        }

        foreach (preg_split('/\r\n|\r|\n/', (string) $request->input('image_urls')) as $url) {
            $url = trim($url);
            if ($url) {
                $product->images()->create([
                    'image_path' => $url,
                    'is_primary' => $sort === 1,
                    'sort_order' => $sort++,
                ]);
            }
        }
    }

    private function syncVariants(Request $request, Product $product): void
    {
        if (! $request->has('variant_sizes')) {
            return;
        }

        $product->variants()->delete();
        $sizes = $request->input('variant_sizes', []);
        $colors = $request->input('variant_colors', []);
        $codes = $request->input('variant_color_codes', []);
        $adjustments = $request->input('variant_adjustments', []);
        $stocks = $request->input('variant_stocks', []);

        foreach ($sizes as $index => $size) {
            if (! $size && empty($colors[$index])) {
                continue;
            }

            $product->variants()->create([
                'size' => $size,
                'color' => $colors[$index] ?? null,
                'color_code' => $codes[$index] ?? null,
                'price_adjustment' => $adjustments[$index] ?? 0,
                'stock_quantity' => $stocks[$index] ?? 0,
                'is_active' => true,
            ]);
        }
    }
}
