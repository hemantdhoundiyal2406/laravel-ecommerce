@extends('layouts.admin')

@section('title', $banner->exists ? 'Edit Banner' : 'Add Banner')

@section('content')
    <div class="admin-card p-4">
        <form action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if($banner->exists) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control" value="{{ old('title', $banner->title) }}" required></div>
                <div class="col-md-6"><label class="form-label">Subtitle</label><input name="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle) }}"></div>
                <div class="col-md-4"><label class="form-label">Button text</label><input name="button_text" class="form-control" value="{{ old('button_text', $banner->button_text) }}"></div>
                <div class="col-md-4"><label class="form-label">Button link</label><input name="button_link" class="form-control" value="{{ old('button_link', $banner->button_link) }}"></div>
                <div class="col-md-4"><label class="form-label">Sort order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"></div>
                <div class="col-md-6"><label class="form-label">Image upload</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                <div class="col-md-6"><label class="form-label">Image URL</label><input type="url" name="image_url" class="form-control" value="{{ old('image_url', str_starts_with($banner->image ?? '', 'http') ? $banner->image : '') }}"></div>
                <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))> <span class="form-check-label">Active</span></label></div>
                <div class="col-12 d-flex gap-2"><button class="btn btn-success">Save Banner</button><a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
            </div>
        </form>
    </div>
@endsection
