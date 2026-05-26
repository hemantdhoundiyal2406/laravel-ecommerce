@extends('layouts.frontend')

@section('title', 'Profile - UrbanCart')

@section('content')
    <section class="container py-4 py-lg-5">
        <h1 class="mb-4">Profile</h1>
        @include('frontend.account._nav')
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="mini-panel p-4">
                    <h2 class="h4">Edit profile</h2>
                    <form action="{{ route('account.profile.update') }}" method="POST">
                        @csrf @method('PATCH')
                        <label class="form-label">Name</label><input name="name" class="form-control mb-3" value="{{ old('name', auth()->user()->name) }}" required>
                        <label class="form-label">Phone</label><input name="phone" class="form-control mb-3" value="{{ old('phone', auth()->user()->phone) }}">
                        <button class="btn btn-brand" type="submit">Save Profile</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mini-panel p-4">
                    <h2 class="h4">Change password</h2>
                    <form action="{{ route('account.password.update') }}" method="POST">
                        @csrf @method('PATCH')
                        <label class="form-label">Current password</label><input type="password" name="current_password" class="form-control mb-3" required>
                        <label class="form-label">New password</label><input type="password" name="password" class="form-control mb-3" required>
                        <label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control mb-3" required>
                        <button class="btn btn-brand" type="submit">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
