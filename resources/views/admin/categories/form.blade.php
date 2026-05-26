@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Category' : 'Add Category')

@section('content')
    <div class="admin-card p-4">
        <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($category->exists) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name', $category->name) }}" required></div>
                <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control" value="{{ old('slug', $category->slug) }}" placeholder="Auto generated if empty"></div>
                <div class="col-md-6"><label class="form-label">Parent Category</label><select name="parent_id" class="form-select"><option value="">None</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Sort order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}"></div>
                <div class="col-md-6"><label class="form-label">Image upload</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                <div class="col-md-6"><label class="form-label">Image URL</label><input type="url" name="image_url" class="form-control" value="{{ old('image_url', str_starts_with($category->image ?? '', 'http') ? $category->image : '') }}"></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea></div>
                <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))> <span class="form-check-label">Active</span></label></div>
                <div class="col-12 d-flex gap-2"><button class="btn btn-success" type="submit">Save Category</button><a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
            </div>
        </form>
    </div>
@endsection
