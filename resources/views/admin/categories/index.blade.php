@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Category management</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i>Add Category</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Image</th><th>Name</th><th>Parent</th><th>Status</th><th>Slug</th><th></th></tr></thead>
                <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td><img class="thumb" src="{{ $category->image_url }}" alt="{{ $category->image_alt }}"></td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->parent?->name ?: '-' }}</td>
                        <td><span class="badge {{ $category->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>{{ $category->slug }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.categories.edit', $category) }}"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No categories.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    </div>
@endsection
