<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin.brands.index', ['brands' => Brand::latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.brands.form', ['brand' => new Brand()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            $data['logo'] = Seo::storeImage($request->file('logo'), 'brands');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $this->validated($request, $brand->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = Seo::storeImage($request->file('logo'), 'brands');
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return back()->with('success', 'Brand deleted.');
    }

    private function validated(Request $request, ?int $ignore = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:brands,slug,'.($ignore ?: 'NULL')],
            'logo' => ['nullable', 'image', 'max:2048'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
