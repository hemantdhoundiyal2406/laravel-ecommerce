@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
    <div class="admin-card p-4">
        <h2 class="h5 mb-3">Review management</h2>
        <div class="table-responsive"><table class="table"><thead><tr><th>Product</th><th>Customer</th><th>Rating</th><th>Comment</th><th>Status</th><th></th></tr></thead><tbody>
            @forelse($reviews as $review)
                <tr><td>{{ $review->product?->name }}</td><td>{{ $review->name }}<div class="small text-muted">{{ $review->email }}</div></td><td>{{ $review->rating }}/5</td><td>{{ $review->comment }}</td><td><span class="badge text-bg-light border">{{ ucfirst($review->status) }}</span></td><td><form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="d-flex gap-2 mb-2">@csrf @method('PATCH')<select name="status" class="form-select form-select-sm">@foreach(['pending','approved','rejected'] as $status)<option value="{{ $status }}" @selected($review->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select><button class="btn btn-sm btn-success">Save</button></form><form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No reviews.</td></tr>
            @endforelse
        </tbody></table></div>{{ $reviews->links() }}
    </div>
@endsection
