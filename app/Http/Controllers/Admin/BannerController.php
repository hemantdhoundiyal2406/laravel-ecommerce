<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banners.index', ['banners' => Banner::orderBy('sort_order')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = Seo::storeImage($request->file('image'), 'banners');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($banner->image && ! str_starts_with($banner->image, 'http')) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = Seo::storeImage($request->file('image'), 'banners');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return back()->with('success', 'Banner deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'subtitle' => ['nullable', 'string', 'max:300'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_link' => ['nullable', 'string', 'max:300'],
            'image' => ['nullable', 'image', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
