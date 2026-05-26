@extends('layouts.admin')

@section('title', 'Banners')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between mb-3"><h2 class="h5">Homepage banners</h2><a class="btn btn-success" href="{{ route('admin.banners.create') }}"><i class="fa-solid fa-plus me-1"></i>Add Banner</a></div>
        <div class="table-responsive"><table class="table"><thead><tr><th>Image</th><th>Title</th><th>Button</th><th>Sort</th><th>Status</th><th></th></tr></thead><tbody>
            @forelse($banners as $banner)
                <tr><td><img class="thumb" src="{{ str_starts_with($banner->image ?? '', 'http') ? $banner->image : ($banner->image ? asset('storage/'.$banner->image) : asset('assets/images/product-placeholder.svg')) }}" alt="{{ $banner->title }}"></td><td><strong>{{ $banner->title }}</strong><div class="small text-muted">{{ $banner->subtitle }}</div></td><td>{{ $banner->button_text }}</td><td>{{ $banner->sort_order }}</td><td><span class="badge {{ $banner->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span></td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.banners.edit', $banner) }}"><i class="fa-solid fa-pen"></i></a> <form class="d-inline" method="POST" action="{{ route('admin.banners.destroy', $banner) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form></td></tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No banners.</td></tr>
            @endforelse
        </tbody></table></div>{{ $banners->links() }}
    </div>
@endsection
