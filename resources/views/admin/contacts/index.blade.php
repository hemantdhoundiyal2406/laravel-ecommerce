@extends('layouts.admin')

@section('title', 'Contact Queries')

@section('content')
    <div class="admin-card p-4">
        <h2 class="h5 mb-3">Contact query management</h2>
        <div class="table-responsive"><table class="table"><thead><tr><th>Name</th><th>Subject</th><th>Email</th><th>Read</th><th>Replied</th><th></th></tr></thead><tbody>
            @forelse($queries as $query)
                <tr><td>{{ $query->name }}</td><td>{{ $query->subject }}</td><td>{{ $query->email }}</td><td>{{ $query->is_read ? 'Yes' : 'No' }}</td><td>{{ $query->is_replied ? 'Yes' : 'No' }}</td><td><a href="{{ route('admin.contacts.show', $query) }}" class="btn btn-sm btn-outline-secondary">View</a></td></tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No queries.</td></tr>
            @endforelse
        </tbody></table></div>{{ $queries->links() }}
    </div>
@endsection
