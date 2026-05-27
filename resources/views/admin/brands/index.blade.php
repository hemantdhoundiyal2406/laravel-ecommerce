@extends('layouts.admin')

@section('title', 'Brands')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Brand management</h2><a href="{{ route('admin.brands.create') }}" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i>Add Brand</a></div>
        <div class="table-responsive"><table class="table"><thead><tr><th>Logo</th><th>Name</th><th>Slug</th><th>Status</th><th></th></tr></thead><tbody>
            @forelse($brands as $brand)
                <tr><td><img class="thumb" src="{{ $brand->logo_url }}" alt="{{ $brand->logo_alt }}"></td><td>{{ $brand->name }}</td><td>{{ $brand->slug }}</td><td><span class="badge {{ $brand->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $brand->is_active ? 'Active' : 'Inactive' }}</span></td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.brands.edit', $brand) }}"><i class="fa-solid fa-pen"></i></a> <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form></td></tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No brands.</td></tr>
            @endforelse
        </tbody></table></div>{{ $brands->links() }}
    </div>
@endsection
