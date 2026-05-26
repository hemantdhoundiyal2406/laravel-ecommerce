@extends('layouts.admin')

@section('title', 'Newsletter')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between mb-3"><h2 class="h5">Newsletter subscribers</h2><a class="btn btn-outline-secondary" href="{{ route('admin.newsletters.export') }}"><i class="fa-solid fa-download me-1"></i>Export CSV</a></div>
        <div class="table-responsive"><table class="table"><thead><tr><th>Email</th><th>Active</th><th>Subscribed</th><th></th></tr></thead><tbody>
            @forelse($subscribers as $subscriber)
                <tr><td>{{ $subscriber->email }}</td><td>{{ $subscriber->is_active ? 'Yes' : 'No' }}</td><td>{{ $subscriber->subscribed_at?->format('d M Y') }}</td><td class="text-end"><form action="{{ route('admin.newsletters.destroy', $subscriber) }}" method="POST">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form></td></tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No subscribers.</td></tr>
            @endforelse
        </tbody></table></div>{{ $subscribers->links() }}
    </div>
@endsection
