@extends('layouts.admin')

@section('title', 'Contact Query')

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between">
            <div><h2 class="h5">{{ $query->subject }}</h2><p class="text-muted">{{ $query->name }} | {{ $query->email }} | {{ $query->phone }}</p></div>
            <form action="{{ route('admin.contacts.destroy', $query) }}" method="POST">@csrf @method('DELETE')<button class="btn btn-outline-danger">Delete</button></form>
        </div>
        <p class="border rounded p-3 bg-light">{{ $query->message }}</p>
        <form action="{{ route('admin.contacts.update', $query) }}" method="POST" class="d-flex gap-3">
            @csrf @method('PATCH')
            <label class="form-check"><input class="form-check-input" type="checkbox" name="is_read" value="1" @checked($query->is_read)> <span class="form-check-label">Read</span></label>
            <label class="form-check"><input class="form-check-input" type="checkbox" name="is_replied" value="1" @checked($query->is_replied)> <span class="form-check-label">Replied</span></label>
            <button class="btn btn-success btn-sm">Save</button>
        </form>
    </div>
@endsection
