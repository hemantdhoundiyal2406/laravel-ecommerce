<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::with('parent')->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.categories.form', [
            'category' => new Category(),
            'parents' => Category::whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = Seo::storeImage($request->file('image'), 'categories');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', [
            'category' => $category,
            'parents' => Category::whereNull('parent_id')->whereKeyNot($category->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($category->image && ! str_starts_with($category->image, 'http')) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = Seo::storeImage($request->file('image'), 'categories');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    private function validated(Request $request, ?int $ignore = null): array
    {
        return $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:categories,slug,'.($ignore ?: 'NULL')],
            'description' => ['nullable', 'string', 'max:1000'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'image_url' => ['nullable', 'url', 'max:500'],
        ]);
    }
}
