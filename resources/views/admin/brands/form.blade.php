@extends('layouts.admin')

@section('title', $brand->exists ? 'Edit Brand' : 'Add Brand')

@section('content')
    <div class="admin-card p-4">
        <form action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if($brand->exists) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name', $brand->name) }}" required></div>
                <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control" value="{{ old('slug', $brand->slug) }}"></div>
                <div class="col-md-6"><label class="form-label">Logo</label><input type="file" name="logo" class="form-control" accept="image/*"></div>
                <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand->is_active ?? true))> <span class="form-check-label">Active</span></label></div>
                <div class="col-12 d-flex gap-2"><button class="btn btn-success">Save Brand</button><a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
            </div>
        </form>
    </div>
@endsection
